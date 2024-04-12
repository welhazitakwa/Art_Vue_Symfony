<?php

namespace App\Test\Controller;

use App\Entity\OeuvreConcours;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class OeuvreConcoursControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $repository;
    private string $path = '/oeuvre/concours/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->repository = $this->manager->getRepository(OeuvreConcours::class);

        foreach ($this->repository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('OeuvreConcour index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'oeuvre_concour[idConcours]' => 'Testing',
            'oeuvre_concour[idOeuvre]' => 'Testing',
        ]);

        self::assertResponseRedirects('/sweet/food/');

        self::assertSame(1, $this->getRepository()->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new OeuvreConcours();
        $fixture->setIdConcours('My Title');
        $fixture->setIdOeuvre('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('OeuvreConcour');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new OeuvreConcours();
        $fixture->setIdConcours('Value');
        $fixture->setIdOeuvre('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'oeuvre_concour[idConcours]' => 'Something New',
            'oeuvre_concour[idOeuvre]' => 'Something New',
        ]);

        self::assertResponseRedirects('/oeuvre/concours/');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getIdConcours());
        self::assertSame('Something New', $fixture[0]->getIdOeuvre());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new OeuvreConcours();
        $fixture->setIdConcours('Value');
        $fixture->setIdOeuvre('Value');

        $this->manager->remove($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/oeuvre/concours/');
        self::assertSame(0, $this->repository->count([]));
    }
}
