<?php

class DatabaseController implements ControllerInterface
{
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
        ) engine = InnoDB;
')->
        prepareAndExecute());
        var_dump($queryBuilder->
        selectRow('
        create table if not exists player
(
	id int not null auto_increment,
	code varchar(32) not null unique,
	primary key (id)
) engine = InnoDB;
')->
        prepareAndExecute());
//TODO тут нужно сделать invite_code уникальным, да и id игрока тоже
        var_dump($queryBuilder->
        selectRow('
        create table if not exists game
(
	id int not null auto_increment,
	turn bool not null,
	game_status_id int not null,
	first_player_id int not null,
	second_player_id int not null,

	primary key (id),
	foreign key (game_status_id) references game_status(id),
	foreign key (first_player_id) references player(id),
	foreign key (second_player_id) references player(id)
) engine = InnoDB;

')->
        prepareAndExecute());
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
) engine = InnoDB;
')->
        prepareAndExecute());
        var_dump($queryBuilder->
        selectRow('
        create table if not exists ship
(
	id int not null auto_increment,
	name varchar(10) not null,
	size int not null,

	primary key (id)
) engine = InnoDB;
')->
        prepareAndExecute());
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
) engine = InnoDB;
')->
        prepareAndExecute());
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
) engine = InnoDB;
')->
        prepareAndExecute());
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
) engine = InnoDB;
')->
        prepareAndExecute());


        /*echo '<br>';

        $table = [
            'game_status',
            'player',
            'game',
            'message',
            'ship',
            'game_field',
            'ship_placement',
            'shot'
        ];

        foreach ($table as $name) {
            echo $name . "<br>";
            $columns = $queryBuilder
                ->selectRow('SHOW columns from ' . $name )->fetchAll();
            //print_r($columns);
            $requiredFields = [];
            foreach ($columns as $row) {
                //var_dump($row['Extra'] !== 'auto_increment');
                if (empty($row['Extra'])) {
                    $requiredFields[] = $row['Field'];
                }
            }

            print_r($requiredFields);
            echo '<br>';
        }*/

        $shipModel = new ShipModel();

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
                ->clear()
                ->insert(['name' => $name, 'size' => substr($name, 0, 1)]);
        }*/


        header('Content-Type: application/json');

        var_dump($shipModel->clear()->query()->select('*')->fetchAll());

        $gameStatusModel = new GameStatusModel();

        $gameStatuses =
            [
                '1' => 'Расстановка кораблей. Начало игры.',
                '2' => 'Идет игра.',
                '3' => 'Игра закончена.'
            ];

        foreach ($gameStatuses as $status => $description) {
            $gameStatusModel
                ->clear()
                ->insert(['status' => $status, 'description' => $description]);
        }

        var_dump($gameStatusModel->clear()->query()->select('*')->fetchAll());


        //TODO DELETE
        //var_dump($queryBuilder->clear()->selectRow('drop table if exists shot, ship_placement, game_field, ship, message, game, player, game_status')->prepareAndExecute());

        //QueryBuilderUtil::getTableColumns('player')


        /*$tableNames =
            [
                'player',
                'game_status',
                'game',
                'message',
                'ship',
                'ship_placement',
                'game_field',
                'shot'
            ];
        $getQuery = [];
        foreach (['create', 'drop'] as $type) {
            $getQuery['action'] = $type;
            foreach ($tableNames as $tableName) {
                $getQuery['table'] = $tableName;
        */

    }

}