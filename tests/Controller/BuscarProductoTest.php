<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BuscarProductoTest extends WebTestCase
{
    //Este test visita una página, 
    //interactúa con ella (rellenado un formulario)
    //y comprueba que se muestra la salida esperada

    public function testSearch(): void
    {
        // This calls KernelTestCase::bootKernel(), and creates a
        // "client" that is acting as the browser
        $client = static::createClient();

        // Request a specific page
        $crawler = $client->request('GET', '/search');

        // Validate a successful response
       // $this->assertResponseIsSuccessful();

        
        // rellenamos el formulario
        //+++++++++++++++++++++++++

        // select the button
        $buttonCrawlerNode = $crawler->selectButton('buscar');

        // retrieve the Form object for the form belonging to this button
        $form = $buttonCrawlerNode->form();

        // set values on a form object + submit the Form object
        $client->submit($form, [
            'busqueda'    => 'Producto 1',
        ]);

        //comprobamos la respuesta (Assertions)
        //+++++++++++++++++++++++++

        //first element matching the given selector contains the expected text
        $this->assertSelectorTextContains('td', 'Producto 1');
        
    }
}
