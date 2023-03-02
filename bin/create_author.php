#!/usr/bin/env php
<?php

use App\Entity\Author;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;
use Doctrine\Common\Persistence\ManagerRegistry;

require __DIR__.'/../vendor/autoload.php';

// create a simple PHP-Doctrine EntityManager instance
$doctrineConfig = Setup::createAnnotationMetadataConfiguration([__DIR__.'/../src/Entity'], true, null, null, false);
$entityManager = EntityManager::create([
    'driver' => 'pdo_sqlite',
    'path' => __DIR__.'/../var/app.db',
], $doctrineConfig);

// create author entity
$author = new Author();
$author->setName('Pierre');
$author->setBio('La bio de Pierre');
$author->setCreatedAt(new DateTime());
$author->setUpdatedAt(new DateTime());

// save author to the database
$entityManager->persist($author);
$entityManager->flush();

echo "Author created successfully!\n";