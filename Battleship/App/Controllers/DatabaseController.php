<?php

namespace Battleship\App\Controllers;

use Battleship\App\Database\Model\GameStatusModel;
use Battleship\App\Database\Model\PlayerModel;
use Battleship\App\Database\Model\ShipModel;
use Battleship\App\Database\QueryBuilder;
use Exception;

class DatabaseController implements ControllerInterface
{
    /** @throws Exception  */
    public function createTables(): void
    {
        $queryBuilder = new QueryBuilder();

        var_dump($queryBuilder->
        selectRow('
        create table if not exists game_status
        (
            id int not null auto_increment,
            status int not null,
            description varchar(100) not null,
            primary key (id)
        ) engine = InnoDB;')->prepareAndExecute());

        var_dump($queryBuilder->
        selectRow('
        create table if not exists player
        (
            id int not null auto_increment,
            code varchar(32) not null unique,
            primary key (id)
        ) engine = InnoDB;')->prepareAndExecute());

        var_dump($queryBuilder->
        selectRow('
        create table if not exists game
        (
            id int not null auto_increment,
            turn bool not null,
            game_status_id int not null,
            first_player_id int not null,
            second_player_id int not null,
            first_ready bool not null default false,
            second_ready bool not null default false,
        
            primary key (id),
            foreign key (game_status_id) references game_status(id),
            foreign key (first_player_id) references player(id),
            foreign key (second_player_id) references player(id)
        ) engine = InnoDB;')->prepareAndExecute());

        var_dump($queryBuilder->
        selectRow('
        create table if not exists message
        (
            id int not null auto_increment,
            created_at int not null,
            content varchar(250) not null,
            game_id int not null,
            player_id int not null,
            
            primary key (id),
            foreign key (game_id) references game(id),
            foreign key (player_id) references player(id)
        ) engine = InnoDB;')->prepareAndExecute());

        var_dump($queryBuilder->
        selectRow('
        create table if not exists ship
        (
            id int not null auto_increment,
            name varchar(10) not null,
            size int not null,
        
            primary key (id)
        ) engine = InnoDB;')->prepareAndExecute());

        var_dump($queryBuilder->
        selectRow('
        create table if not exists game_field
        (
            id int not null auto_increment,
            game_id int not null,
            player_id int not null,
        
        
            primary key (id),
            foreign key (game_id) references game(id),
            foreign key (player_id) references player(id)
        ) engine = InnoDB;')->prepareAndExecute());

        var_dump($queryBuilder->
        selectRow('
        create table if not exists ship_placement
        (
            id int not null auto_increment,
            coordinate_x int not null,
            coordinate_y int not null,
            orientation bool not null,
            ship_id int not null,
            game_field_id int not null,
        
            primary key (id),
            foreign key (ship_id) references ship(id),
            foreign key (game_field_id) references game_field(id)
        ) engine = InnoDB;')->prepareAndExecute());

        var_dump($queryBuilder->
        selectRow('
        create table if not exists shot
        (
            id int not null auto_increment,
            coordinate_x int not null,
            coordinate_y int not null,
            game_field_id int not null,
        
            primary key (id),
            foreign key (game_field_id) references game_field(id)
        ) engine = InnoDB;')->prepareAndExecute());

        $shipModel = ShipModel::getInstance();

        $shipName =
            [
                '1-1',
                '1-2',
                '1-3',
                '1-4',
                '2-1',
                '2-2',
                '2-3',
                '3-1',
                '3-2',
                '4-1'
            ];

        /*foreach ($shipName as $name) {
            $shipModel
                ->insert(['name' => $name, 'size' => substr($name, 0, 1)]);
        }*/

        header('Content-Type: application/json');

        var_dump($shipModel->query()->select('*')->fetchAll());

        $gameStatusModel = new GameStatusModel();

        $gameStatuses =
            [
                '1' => 'Расстановка кораблей. Начало игры.',
                '2' => 'Идет игра.',
                '3' => 'Игра закончена.'
            ];

        /*foreach ($gameStatuses as $status => $description) {
            $gameStatusModel->insert(['status' => $status, 'description' => $description]);
        }*/

        var_dump($gameStatusModel->query()->select('*')->fetchAll());

        $player = new PlayerModel();

        /*var_dump($player->query()->where('id', '=', 7)->select()->fetch());
        var_dump($player->update('code', '=', 'ГИГАНТ', 'БОЛЬШОЙ'));
        var_dump($player->query()->where('id', '=', 7)->select()->fetch());*/

        //DELETE ALL TABLES
        //var_dump($queryBuilder->clear()->selectRow('drop table if exists shot, ship_placement, game_field, ship, message, game, player, game_status')->prepareAndExecute());

    }

}