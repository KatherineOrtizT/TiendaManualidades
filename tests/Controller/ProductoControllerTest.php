<?php

namespace App\Test\Controller;

use App\Entity\Producto;
use App\Repository\ProductoRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductoControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private ProductoRepository $repository;
    private string $path = '/producto/';

    protected function setUp(): void
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $this->client = static::createClient();
        $this->repository = static::getContainer()->get('doctrine')->getRepository(Producto::class);

        foreach ($this->repository->findAll() as $object) {
            $this->repository->remove($object, true);
        }
    }


    public function testSomething(): void
    {
        
        // Request a specific page
        $crawler = $this->client->request('GET', '/homepage');

        // Validate a successful response and some content
        /*Las dos siguiente líneas hacen lo mismo y evalúan si la respuesta del 
        la petición al controlador redirige a esa ruta. Es decir, si el request
        que vemos unas líneas arriba a /homepage emite una respuesta a la ruta
        'http://localhost/homepage/'
        */
        $this->assertSame('http://localhost/homepage/', $this->client->getResponse()->headers->get('Location'));
        //$this->assertResponseRedirects('hhttp://localhost/homepage/', 301);
        $this->assertSelectorTextContains('h2', 'Testimoniales');
    }

    /* public function testIndex(): void
    {
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Producto index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    } */

    public function testNew(): void
    {
        $originalNumObjectsInRepository = count($this->repository->findAll());

        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'producto[nombre]' => 'Testing',
            'producto[descripcion]' => 'Testing',
            'producto[precio]' => 'Testing',
            'producto[descuento]' => 'Testing',
            'producto[categoria]' => 'Testing',
            'producto[material]' => 'Testing',
            'producto[color]' => 'Testing',
        ]);

        self::assertResponseRedirects('/homepage');

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Producto();
        $fixture->setNombre('My Title');
        $fixture->setDescripcion('My Title');
        $fixture->setPrecio('My Title');
        $fixture->setDescuento('My Title');
        $fixture->setCategoria('My Title');
        $fixture->setMaterial('My Title');
        $fixture->setColor('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Producto');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Producto();
        $fixture->setNombre('My Title');
        $fixture->setDescripcion('My Title');
        $fixture->setPrecio('My Title');
        $fixture->setDescuento('My Title');
        $fixture->setCategoria('My Title');
        $fixture->setMaterial('My Title');
        $fixture->setColor('My Title');

        $this->repository->save($fixture, true);

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'producto[nombre]' => 'Something New',
            'producto[descripcion]' => 'Something New',
            'producto[precio]' => 'Something New',
            'producto[descuento]' => 'Something New',
            'producto[categoria]' => 'Something New',
            'producto[material]' => 'Something New',
            'producto[color]' => 'Something New',
        ]);

        self::assertResponseRedirects('/homepage');

        $fixture = $this->repository->findAll();

        self::assertSame('Something New', $fixture[0]->getNombre());
        self::assertSame('Something New', $fixture[0]->getDescripcion());
        self::assertSame('Something New', $fixture[0]->getPrecio());
        self::assertSame('Something New', $fixture[0]->getDescuento());
        self::assertSame('Something New', $fixture[0]->getCategoria());
        self::assertSame('Something New', $fixture[0]->getMaterial());
        self::assertSame('Something New', $fixture[0]->getColor());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();

        $originalNumObjectsInRepository = count($this->repository->findAll());

        $fixture = new Producto();
        $fixture->setNombre('My Title');
        $fixture->setDescripcion('My Title');
        $fixture->setPrecio('My Title');
        $fixture->setDescuento('My Title');
        $fixture->setCategoria('My Title');
        $fixture->setMaterial('My Title');
        $fixture->setColor('My Title');

        $this->repository->save($fixture, true);

        self::assertSame($originalNumObjectsInRepository + 1, count($this->repository->findAll()));

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertSame($originalNumObjectsInRepository, count($this->repository->findAll()));
        self::assertResponseRedirects('/homepage');
    }


    public function testBuscar01(): void
    {
        // Request a specific page
        $crawler = $this->client->request('GET', sprintf('%ssearch', $this->path, ['busqueda' => 'Kit']));
        $this->client->catchExceptions(false);
        // Validate a successful response and some content
        /*Las dos siguiente líneas hacen lo mismo y evalúan si la respuesta del 
        la petición al controlador redirige a esa ruta.
        */
        //$this->assertSame('http://localhost/producto/catalogo', $this->client->getResponse()->headers->get('Location'));
        $this->assertResponseRedirects('http://localhost/producto/catalogo', 301);
        $this->assertResponseStatusCodeSame(200);

        $this->assertEquals(
            2,
            $crawler->filter('html td:contains("Kit")')->count()
        );
        
        //$this->assertSelectorTextContains('td', 'No se han encontrado resultados');
    }


    public function testBuscar02(): void
    {
        // Request a specific page
        $crawler = $this->client->request('GET', sprintf('%ssearch', $this->path, ['busqueda' => 'ky']));

        // Validate a successful response and some content
        /*Las dos siguiente líneas hacen lo mismo y evalúan si la respuesta del 
        la petición al controlador redirige a esa ruta.
        */
        //$this->assertSame('http://localhost/producto/catalogo', $this->client->getResponse()->headers->get('Location'));
        $this->assertResponseRedirects('hhttp://localhost/producto/catalogo', 301);
        $this->assertResponseStatusCodeSame(200);

        $this->assertEquals(
            0,
            $crawler->filter('html td:contains("ky")')->count()
        );
        
        $this->assertSelectorTextContains('td', 'No se han encontrado resultados');
    }
}
