<?php
//src/Command/SendEmailCommand.php

namespace App\Command;

use DateTime;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
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
		$this->setDescription('Command for send self email');
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

		foreach ($products as $product)
		{

			$datereturn = $product->getReturnDate($now->format('d,m,Y'));
			$datereturncomp = $datereturn->format('d,m,Y');

			if ($datecomp < $datereturncomp && $product->getIdUser() !== NULL)
			{
				$email = (new TemplatedEmail())
					->from('admin@acs.com')
					->to($product->getIdUser()->getEmail())
					->subject('Date de retour de votre location.')
					->htmlTemplate('emails/return.html.twig')
					->context([
						'product' => $product,
						'username' => $product->getIdUser()->getUsername(),
						'expiration_date' => new DateTime('+15 days')
					]);

					$this->mailer->send($email);
					$output->writeln('Successful you send a self email');
					return 1;
			}
		}
	}
}
