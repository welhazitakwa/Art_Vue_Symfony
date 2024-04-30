<?php

namespace App\Test\Controller;

use App\Entity\Offreenchere;
use App\Repository\OffreenchereRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OffreenchereControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private OffreenchereRepository $repository;
    private string $path = '/offreenchere/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Offreenchere::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Offreenchere index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'offreenchere[montant]' => 'Testing',
            'offreenchere[date]' => 'Testing',
            'offreenchere[idUtilisateur]' => 'Testing',
            'offreenchere[idVenteenchere]' => 'Testing',
        ]);

        self::assertResponseRedirects('/offreenchere/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Offreenchere();
        $fixture->setMontant('My Title');
        $fixture->setDate('My Title');
        $fixture->setIdUtilisateur('My Title');
        $fixture->setIdVenteenchere('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Offreenchere');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Offreenchere();
        $fixture->setMontant('My Title');
        $fixture->setDate('My Title');
        $fixture->setIdUtilisateur('My Title');
        $fixture->setIdVenteenchere('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'offreenchere[montant]' => 'Something New',
            'offreenchere[date]' => 'Something New',
            'offreenchere[idUtilisateur]' => 'Something New',
            'offreenchere[idVenteenchere]' => 'Something New',
        ]);

        self::assertResponseRedirects('/offreenchere/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getMontant());
        self::assertSame('Something New', $fixture[0]->getDate());
        self::assertSame('Something New', $fixture[0]->getIdUtilisateur());
        self::assertSame('Something New', $fixture[0]->getIdVenteenchere());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Offreenchere();
        $fixture->setMontant('My Title');
        $fixture->setDate('My Title');
        $fixture->setIdUtilisateur('My Title');
        $fixture->setIdVenteenchere('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/offreenchere/');
    }
}
