<?php

namespace NS\PurearthBundle\Command;

use NS\Purearth\User\User;
use NS\PurearthBundle\Service\MailchimpAPI;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Helper\ProgressBar;

class SyncMailingListCommand extends ContainerAwareCommand
{
    protected $encoder,
        $em,
        $mailer,
        $go,
        $limit,
        $importCycle;

    /* @var $mailchimp MailchimpAPI */
    private $mailchimp;

    /**
     * @var LoggerInterface
     */
    private $logger;

    protected function configure()
    {
        $this->setName('ns:mailinglist:sync')
            ->setDescription('Sync users database with Mailchimp')
            ->addOption('limit', null, InputOption::VALUE_REQUIRED, 'Limit results')
            ->addOption('go', null, InputOption::VALUE_NONE, 'Toggle test run', null);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $this->mailchimp = $this->getContainer()->get('ns.purearth.mailchimp');
        $this->logger = $this->getContainer()->get('logger');

        ini_set('memory_limit', '1G');
        $this->go = $input->getOption('go');
        $this->limit = $input->getOption('limit');
        $this->em = $this->getContainer()->get('doctrine')->getManager();

        $output->writeln('Retrieving users');

        $users = $this->em->getRepository(User::class)->findAll();

        $processed = 0;
        $failed = 0;
        $count = 0;
        $subscribed = 0;
        $unsubscribed = 0;
        $progress = new ProgressBar($output, count($users));
        $output->writeln('Syncing users');

        /**
         * @var User $user
         */
        foreach($users as $user)
        {
            if($user->getEmail())
            {
                $subscriber = $this->mailchimp->quickGetSubscriber($user->getEmail(), $user->getMailchimpSubscriberHash());
                if($subscriber['status'] == 'subscribed')
                {
                    $subscribed++;
                }
                else
                {
                    $unsubscribed++;
                }

                if($this->go)
                {
                    try
                    {
                        $user->setMailchimpSubscriberHash($subscriber['id']);
                        $this->em->persist($user);
                    }
                    catch(\Exception $e)
                    {
                        $this->logger->info('Mailing list update failed', ['exception'=>$e]);
                        $failed++;
                    }
                }

                $processed++;
                $progress->advance();
            }
            unset($user);

            $count++;

            if(!$this->go && $this->limit && $count >= $this->limit)
            {
                break;
            }
        }

        if($this->go)
        {
            $this->em->flush();
        }

        $progress->finish();
        $output->writeLn('');
        $output->writeLn('Users processed: '.count($users));
        $output->writeLn('Users synced: '.$processed);
        $output->writeLn('Failed: '.$failed);
        $output->writeLn('Total subscribed: '.$subscribed);
        $output->writeLn('Total unsubscibed: '.$unsubscribed);

        if(!$this->go)
        {
            $output->writeLn('This was a test run. No updates have been performed.  Re-run with --go to update list.');
        }

        return 0;
    }
}
