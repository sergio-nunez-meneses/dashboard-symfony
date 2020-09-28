<?php
//src/Command/SendEmailCommand.php

namespace App\Command;

use App\Entity\Users;
use App\Entity\Products;
use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SendEmailCommand extends Command
{
	protected static $defaultName = 'send:email';

	private $mailer;
	
	public function __construct(MailerInterface $mailer)
	{
		$this->mailer = $mailer;
        
		parent::__construct();
	}

	protected function configure()
	{
		$this
			->setDescription('Command for send self email')
		;
	}
    
	protected function execute(InputInterface $input, OutputInterface $output)
	{
		$output->writeln([
			'Command Send Self Email',
			'============'
		]);


			$email = (new Email())
				->from('admin@acs.com') 
				->to('o.quevillart@codeur.online') 
				->priority(Email::PRIORITY_HIGH) 
				->subject('I love Me')
				->text('Lorem ipsum...') 
				->html('<h1>Lorem ipsum</h1> <p>...</p>')
			;
				$this->mailer->send($email);

				$output->writeln('Successful you send a self email');
	}
}