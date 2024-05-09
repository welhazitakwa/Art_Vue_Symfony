<?php

namespace App\Test\Controller;

use App\Entity\Venteencheres;
use App\Repository\VenteencheresRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VenteencheresControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private VenteencheresRepository $repository;
    private string $path = '/venteencheres/';
    private EntityManagerInterface $manager;

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Venteencheres::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Venteenchere index');

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
            'venteenchere[datedebut]' => 'Testing',
            'venteenchere[datefin]' => 'Testing',
            'venteenchere[prixdepart]' => 'Testing',
            'venteenchere[statue]' => 'Testing',
            'venteenchere[idExposition]' => 'Testing',
            'venteenchere[idUtilisateur]' => 'Testing',
        ]);

        self::assertResponseRedirects('/venteencheres/');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Venteencheres();
        $fixture->setDatedebut('My Title');
        $fixture->setDatefin('My Title');
        $fixture->setPrixdepart('My Title');
        $fixture->setStatue('My Title');
        $fixture->setIdExposition('My Title');
        $fixture->setIdUtilisateur('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Venteenchere');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Venteencheres();
        $fixture->setDatedebut('My Title');
        $fixture->setDatefin('My Title');
        $fixture->setPrixdepart('My Title');
        $fixture->setStatue('My Title');
        $fixture->setIdExposition('My Title');
        $fixture->setIdUtilisateur('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'venteenchere[datedebut]' => 'Something New',
            'venteenchere[datefin]' => 'Something New',
            'venteenchere[prixdepart]' => 'Something New',
            'venteenchere[statue]' => 'Something New',
            'venteenchere[idExposition]' => 'Something New',
            'venteenchere[idUtilisateur]' => 'Something New',
        ]);

        self::assertResponseRedirects('/venteencheres/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getDatedebut());
        self::assertSame('Something New', $fixture[0]->getDatefin());
        self::assertSame('Something New', $fixture[0]->getPrixdepart());
        self::assertSame('Something New', $fixture[0]->getStatue());
        self::assertSame('Something New', $fixture[0]->getIdExposition());
        self::assertSame('Something New', $fixture[0]->getIdUtilisateur());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Venteencheres();
        $fixture->setDatedebut('My Title');
        $fixture->setDatefin('My Title');
        $fixture->setPrixdepart('My Title');
        $fixture->setStatue('My Title');
        $fixture->setIdExposition('My Title');
        $fixture->setIdUtilisateur('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/venteencheres/');
    }
}
