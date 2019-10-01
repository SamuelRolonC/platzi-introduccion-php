<?php


namespace App\Commands;


use App\Models\Message;
use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendMailCommand extends Command
{
    protected static $defaultName = 'app:send-mail';

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $pendingMessage = Message::where('sent',false)->first();

        if ($pendingMessage) {
            $transport = (new Swift_SmtpTransport(getenv('SMTP_HOST'), getenv('SMTP_PORT')))
                ->setUsername(getenv('SMTP_USER'))
                ->setPassword(getenv('SMTP_PASS'))
            ;

            $mailer = new Swift_Mailer($transport);
            $message = (new Swift_Message('Contact from Portfolios'))
                ->setFrom([$pendingMessage['email'] => $pendingMessage['name']])
                ->setTo(['receiver@domain.org', 'contacts@domain.org' => 'Contacts'])
                ->setBody($pendingMessage['message'])
            ;

            if ($mailer->send($message)) {
                $pendingMessage->sent = true;
                $pendingMessage->update();
            }

        }
    }
}