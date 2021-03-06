<?php
/**
 *
 *
 * All rights reserved.
 *
 * @author Okulov Anton
 * @email qantus@mail.ru
 * @version 1.0
 * @date 10/04/16 10:59
 */

namespace Phact\Tests;

use Phact\Orm\ConnectionManager;
use Phact\Orm\TableManager;

class DatabaseTest extends AppTest
{
    protected function getComponents()
    {
        return [
            'db' => [
                'class' => ConnectionManager::class,
                'connections' => $this->getConnections()
            ]
        ];
    }

    public function setUp()
    {
        parent::setUp();
        $tableManager = new TableManager();
        $models = $this->useModels();
        if ($models) {
            $tableManager->create($models);
        }
    }

    public function tearDown()
    {
        parent::tearDown();
        $tableManager = new TableManager();
        $models = $this->useModels();
        if ($models) {
            $tableManager->drop($models);
        }
    }

    public function useModels()
    {
        return [];
    }
    
    public function getConnections()
    {
        $dir = implode(DIRECTORY_SEPARATOR,[__DIR__, '..', 'config']);
        $local = implode(DIRECTORY_SEPARATOR,[$dir, 'connections_local.php']);
        $public = implode(DIRECTORY_SEPARATOR,[$dir, 'connections.php']);
        if (is_file($local)) {
            return require($local);
        } elseif (is_file($public)) {
            return require($public);
        }
        return [];
    }
}