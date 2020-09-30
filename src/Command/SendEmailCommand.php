<?php
//src/Command/SendEmailCommand.php

namespace App\Command;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Mime\Email;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class SendEmailCommand extends Command
{
	protected static $defaultName = 'send:email';

	private $mailer;

	private $container;


	
	public function __construct(MailerInterface $mailer, ContainerInterface $container, EntityManagerInterface $em)
	{
        
		parent::__construct();

		$this->mailer = $mailer;
		$this->container = $container;
		$this->em = $em;

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

		$now = new \DateTime();

		$datecomp = $now->format('d,m,Y');

		$em = $this->container->get('doctrine')->getRepository('App:Products');

		$products = $em->findAll();

		foreach ($products as $product) {

			$datereturn = $product->getReturnDate($now);

			$datereturncomp = $datereturn->format('d,m,Y');
			
			if ($datecomp >= $datereturncomp) {

			$email = (new Email())
				->from('admin@acs.com') 
				->to($product->getIdUser()->getUsername())
				->priority(Email::PRIORITY_HIGH) 
				->subject('Date de retour de votre location.')
				->text('Retour location') 
				->html("<h1>Retour de location</h1> <p>Il vous reste 15 jours pour rendre l'article loué.");

				$this->mailer->send($email);

				$output->writeln('Successful you send a self email');
			}	
		}		
	}
}