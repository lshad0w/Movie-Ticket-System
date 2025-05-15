<?php 
    include('dbconnect.php');

    if (!$con) {
        die("Connection failed: " . mysqli_connect_error());
    }
    else {
        $sqlMovie = 
        "CREATE TABLE movies (
            movie_id INT AUTO_INCREMENT PRIMARY KEY,
            title VARCHAR(255) NOT NULL,
            year_release INT NOT NULL,
            `rated` VARCHAR(10),
            `time` TIME,
            `description` TEXT,
            `poster` VARCHAR(255),
            `trailer` VARCHAR(255)
        );";

        $sqlShowTime = 
        "CREATE TABLE showtimes (
            showtime_id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,
            movie_id INT NOT NULL,
            showtime DATETIME NOT NULL,
            INDEX movie_id(movie_id),
            FOREIGN KEY (movie_id) REFERENCES movies(movie_id) ON DELETE RESTRICT ON UPDATE CASCADE
        );";
        $sqlSeats =
        "CREATE TABLE reservation (
            reservation_id INT AUTO_INCREMENT PRIMARY KEY,
            seat_number INT NOT NULL,
            user_id INT NOT NULL,
            showtime_id INT NOT NULL,
            status ENUM('available', 'reserved') DEFAULT 'available',
            FOREIGN KEY (showtime_id) REFERENCES showtimes(showtime_id),
            FOREIGN KEY (user_id) REFERENCES users(user_id)
            );";
         $sqlGenre = 
         "CREATE TABLE genre (
             movie_id INT NOT NULL,
             genre VARCHAR(255),
             FOREIGN KEY (movie_id) REFERENCES movies(movie_id) ON DELETE RESTRICT ON UPDATE CASCADE
         );";
         $sqlCast = 
         "CREATE TABLE actors (
             movie_id INT NOT NULL,
             actors VARCHAR(255),
             FOREIGN KEY (movie_id) REFERENCES movies(movie_id) ON DELETE RESTRICT ON UPDATE CASCADE
         );";
         $sqlPayment =
         "CREATE TABLE IF NOT EXISTS payments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name_on_card VARCHAR(100) NOT NULL,
            email_address VARCHAR(100) NOT NULL,
            card_number VARCHAR(16) NOT NULL,
            expiration_date DATE NOT NULL,
            cvv VARCHAR(4) NOT NULL,
            balance INT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        );";
        $sqlMovieData =
        "INSERT INTO movies (title, year_release, `rated`, `time`, `description`, `poster`, `trailer`) VALUES
            ('One Punch Man', 2024, 'R', '00:25:00', 'After rigorously training for three years, the ordinary Saitama has gained immense strength which allows him to take out anyone and anything with just one punch. He decides to put his new skill to good use by becoming a hero. However, he quickly becomes bored with easily defeating monsters, and wants someone to give him a challenge to bring back the spark of being a hero.', 'one punch man.jpg', 'one punch man.mp4'),
            ('The Idea of You', 2024, 'R', '01:55:00', 'Solène, a 40-year-old single mom, begins an unexpected romance with 24-year-old Hayes Campbell, the lead singer of August Moon, the hottest boy band on the planet.', 'the idea of you.jpg', 'the idea of you.mp4'),
            ('Kingdom of the Planet of the Apes', 2024, 'PG-13', '02:25:00', 'Many years after the reign of Caesar, a young ape goes on a journey that will lead him to question everything he\'s been taught about the past and make choices that will define a future for apes and humans alike.', 'kingdom of the planet of the apes.jpg', 'kingdom of the planet of the apes.mp4'),
            ('The Fall Guy', 2024, 'PG-13', '02:06:00', 'A down-and-out stuntman must find the missing star of his ex-girlfriend\'s blockbuster film.', 'the fall guy.jpg', 'the fall guy.mp4'),
            ('Civil War', 2024, 'R', '01:49:00', 'A journey across a dystopian future America, following a team of military-embedded journalists as they race against time to reach DC before rebel factions descend upon the White House.', 'civil war.jpg', 'civil war.mp4'),
            ('Twisters', 2024, NULL, NULL, 'An update to the 1996 film \'Twister\', which centered on a pair of storm chasers who risk their lives in an attempt to test an experimental weather alert system.', 'twisters.jpg', 'twisters.mp4'),
            ('Ghostbusters: Frozen Empire', 2024, 'PG-13', '01:55:00', 'When the discovery of an ancient artifact unleashes an evil force, Ghostbusters new and old must join forces to protect their home and save the world from a second ice age.', 'ghostbusters frozen empire.jpg', 'ghostbusters frozen empire.mp4'),
            ('Deadpool & Wolverine', 2024, NULL, NULL, 'Wolverine is recovering from his injuries when he crosses paths with the loudmouth, Deadpool. They team up to defeat a common enemy.', 'deadpool and wolverine.jpg', 'deadpool and wolverine.mp4'),
            ('Monkey Man', 2024, 'PG-13', '02:25:00', 'An anonymous young man unleashes a campaign of vengeance against the corrupt leaders who murdered his mother and continue to systematically victimize the poor and powerless.', 'monkey man.jpg', 'monkey man.mp4'),
            ('Shaitaan', 2024, 'Not Rated', '02:12:00', 'A family\'s getaway turns terrifying when an intruder possesses the teen daughter\'s body, putting her at the mercy of his increasingly sinister commands.', 'shaitaan.jpg', 'shaitaan.mp4'),
            ('Tarot', 2024, 'PG-13', '01:32:00', 'When a group of friends recklessly violates the sacred rule of Tarot readings, they unknowingly unleash an unspeakable evil trapped within the cursed cards. One by one, they come face to face with fate and end up in a race against death.', 'tarot.jpg', 'tarot.mp4'),
            ('Godzilla x Kong: The New Empire', 2024, 'PG-13', '02:25:00', 'Two ancient titans, Godzilla and Kong, clash in an epic battle as humans unravel their intertwined origins and connection to Skull Island\'s mysteries.', 'godzilla x kong the new empire.jpg', 'godzilla x kong the new empire.mp4'),
            ('The Garfield Movie', 2024, 'PG', '01:41:00', 'After Garfield\'s unexpected reunion with his long-lost father, ragged alley cat Vic, he and his canine friend Odie are forced from their perfectly pampered lives to join Vic on a risky heist.', 'the garfield movie.jpg', 'the garfield movie.mp4'),
            ('Kung Fu Panda 4', 2024, 'PG', '01:34:00', 'After Po is tapped to become the Spiritual Leader of the Valley of Peace, he needs to find and train a new Dragon Warrior, while a wicked sorceress plans to re-summon all the master villains whom Po has vanquished to the spirit realm.', 'kung fu panda 4.jpg', 'kung fu panda 4.mp4'),
            ('Despicable Me 4', 2024, 'PG', '01:35:00', 'Gru, Lucy, Margo, Edith, and Agnes welcome a new member to the family, Gru Jr., who is intent on tormenting his dad. Gru faces a new nemesis in Maxime Le Mal and his girlfriend Valentina, and the family is forced to go on the run.', 'despicable me 4.jpg', 'despicable me 4.mp4'),
            ('Immaculate', 2024, 'R', '01:29:00', 'Cecilia, a woman of devout faith, is warmly welcomed to the picture-perfect Italian countryside where she is offered a new role at an illustrious convent. But it becomes clear to Cecilia that her new home harbors dark and horrifying secrets.', 'immaculate.jpg', 'immaculate.mp4'),
            ('A Quiet Place: Day One', 2024, NULL, '01:40:00', 'Experience the day the world went quiet.', 'a quiet place day one.jpg', 'a quiet place day one.mp4'),
            ('Madame Web', 2024, 'PG-13', '01:56:00', 'Cassandra Webb is a New York metropolis paramedic who begins to demonstrate signs of clairvoyance. Forced to challenge revelations about her past, she needs to safeguard three young women from a deadly adversary who wants them destroyed.', 'madame web.jpg', 'madame web.mp4'),
            ('Mufasa: The Lion King', 2024, NULL, NULL, 'Simba, having become king of the Pride Lands, is determined for his cub to follow in his paw prints while the origins of his late father Mufasa are explored.', 'mufasa the lion king.jpg', 'mufasa the lion king.mp4'),
            ('The First Omen', 2024, 'R', '01:59:00', 'A young American woman is sent to Rome to begin a life of service to the church, but encounters a darkness that causes her to question her faith and uncovers a terrifying conspiracy that hopes to bring about the birth of evil incarnate.', 'the first omen.jpg', 'the first omen.mp4'),
            ('Land of Bad', 2024, 'R', '01:53:00', 'A US Army special forces unit is ambushed during a mission to retrieve an intelligence asset and their only remaining hope lies with a remote Air Force drone operator assisting them through a brutal 48-hour battle for survival.', 'land of bad.jpg', 'land of bad.mp4'),
            ('The Strangers: Chapter 1', 2024, 'R', '01:31:00', 'After their car breaks down in an eerie small town, a young couple is forced to spend the night in a remote cabin. Panic ensues as they are terrorized by three masked strangers who strike with no mercy and seemingly no motive.', 'the strangers chapter 1.jpg', 'the strangers chapter 1.mp4'),
            ('The Beekeeper', 2024, 'R', '01:45:00', 'One man\'s brutal campaign for vengeance takes on national stakes after he is revealed to be a former operative of a powerful and clandestine organization known as &ldquo;Beekeepers.&ldquo;', 'the beekeeper.jpg', 'the beekeeper.mp4'),
            ('Arcadian', 2024, 'R', '01:32:00', 'A father and his twin teenage sons fight to survive in a remote farmhouse at the end of the end of the world.', 'arcadian.jpg', 'arcadian.mp4'),
            ('Road House', 2024, 'R', '02:01:00', 'Ex-UFC fighter Dalton takes a job as a bouncer at a Florida Keys roadhouse, only to discover that this paradise is not all it seems.', 'road house.jpg', 'road house.mp4'),
            ('The Crow', 2024, NULL, NULL, 'When soulmates Eric and Shelly are brutally murdered, Eric is given the chance to save his true love by sacrificing himself and sets out to seek revenge, traversing the worlds of the living and the dead to put the wrong things right.', 'the crow.jpg', 'the crow.mp4'),
            ('Arthur the King', 2024, 'PG-13', '01:47:00', 'An adventure racer adopts a stray dog named Arthur to join him in an epic endurance race.', 'arthur the king.jpg', 'arthur the king.mp4');
            ";        
        $sqlShowTimeData = 
        "INSERT INTO showtimes (movie_id, showtime) VALUES
            ('1', '2024-06-01 14:00:00'),
            ('1', '2024-06-02 16:30:00'),
            ('1', '2024-06-03 19:00:00'),
            ('2', '2024-06-04 21:30:00'),
            ('2', '2024-06-05 14:00:00'),
            ('2', '2024-06-06 16:30:00'),
            ('3', '2024-06-07 19:00:00'),
            ('3', '2024-06-08 21:30:00'),
            ('3', '2024-06-09 14:00:00'),
            ('4', '2024-06-10 16:30:00'),
            ('4', '2024-06-11 19:00:00'),
            ('4', '2024-06-12 21:30:00'),
            ('5', '2024-06-13 14:00:00'),
            ('5', '2024-06-14 16:30:00'),
            ('5', '2024-06-15 19:00:00'),
            ('6', '2024-06-16 21:30:00'),
            ('6', '2024-06-17 14:00:00'),
            ('6', '2024-06-18 16:30:00'),
            ('7', '2024-06-19 19:00:00'),
            ('7', '2024-06-20 21:30:00'),
            ('7', '2024-06-21 14:00:00'),
            ('8', '2024-06-22 16:30:00'),
            ('8', '2024-06-23 19:00:00'),
            ('8', '2024-06-24 21:30:00'),
            ('9', '2024-06-25 14:00:00'),
            ('9', '2024-06-26 16:30:00'),
            ('9', '2024-06-27 19:00:00'),
            ('10', '2024-06-28 21:30:00'),
            ('10', '2024-06-29 14:00:00'),
            ('10', '2024-06-30 16:30:00'),
            ('11', '2024-07-01 19:00:00'),
            ('11', '2024-07-02 21:30:00'),
            ('11', '2024-07-03 14:00:00'),
            ('12', '2024-07-04 16:30:00'),
            ('12', '2024-07-05 19:00:00'),
            ('12', '2024-07-06 21:30:00'),
            ('13', '2024-07-07 14:00:00'),
            ('13', '2024-07-08 16:30:00'),
            ('13', '2024-07-09 19:00:00'),
            ('14', '2024-07-10 21:30:00'),
            ('14', '2024-07-11 14:00:00'),
            ('14', '2024-07-12 16:30:00'),
            ('15', '2024-07-13 19:00:00'),
            ('15', '2024-07-14 21:30:00'),
            ('15', '2024-07-15 14:00:00'),
            ('16', '2024-07-16 16:30:00'),
            ('16', '2024-07-17 19:00:00'),
            ('16', '2024-07-18 21:30:00'),
            ('17', '2024-07-19 14:00:00'),
            ('17', '2024-07-20 16:30:00'),
            ('17', '2024-07-21 19:00:00'),
            ('18', '2024-07-22 21:30:00'),
            ('18', '2024-07-23 14:00:00'),
            ('18', '2024-07-24 16:30:00'),
            ('19', '2024-07-25 19:00:00'),
            ('19', '2024-07-26 21:30:00'),
            ('19', '2024-07-27 14:00:00'),
            ('20', '2024-07-28 16:30:00'),
            ('20', '2024-07-29 19:00:00'),
            ('20', '2024-07-30 21:30:00'),
            ('21', '2024-07-31 14:00:00'),
            ('21', '2024-08-01 16:30:00'),
            ('21', '2024-08-02 19:00:00'),
            ('22', '2024-08-03 21:30:00'),
            ('22', '2024-08-04 14:00:00'),
            ('22', '2024-08-05 16:30:00'),
            ('23', '2024-08-06 19:00:00'),
            ('23', '2024-08-07 21:30:00'),
            ('23', '2024-08-08 14:00:00'),
            ('24', '2024-08-09 16:30:00'),
            ('24', '2024-08-10 19:00:00'),
            ('24', '2024-08-11 21:30:00'),
            ('25', '2024-08-12 14:00:00'),
            ('25', '2024-08-13 16:30:00'),
            ('25', '2024-08-14 19:00:00'),
            ('26', '2024-08-15 21:30:00'),
            ('26', '2024-08-16 14:00:00'),
            ('26', '2024-08-17 16:30:00'),
            ('27', '2024-08-18 19:00:00'),
            ('27', '2024-08-19 21:30:00'),
            ('27', '2024-08-20 14:00:00');";
        $sqlUserAcc = 
        "INSERT INTO users(email,passcode) 
                VALUES
                ('user1234d@gmail.com','user123'),
                ('student1234@gmail.com','student123'),
                ('furina1234@gmail.com','ihavethepower')";
        $sqlAdminAcc = 
        "INSERT INTO admins(admin_key,passcode) 
                VALUES
                ('appleapple','Admin1'),
                ('melonmelon','Admin2'),
                ('wheatbread','Admin3')";

        $sqlUser = 
        "CREATE TABLE users(
                user_id INT(18) AUTO_INCREMENT Primary Key, 
                email Varchar(30) NOT NUll, 
                passcode Varchar(30) Not Null
                )"; 
        $sqlAdmin = 
        "CREATE TABLE admins( 
                admin_id INT(18) AUTO_INCREMENT Primary Key,
                admin_key Varchar(20) Not Null,
                passcode Varchar(30) Not Null
                )"; 
        $sqlCastData = 
        "INSERT INTO actors (movie_id, actors) VALUES
        (1, 'Studios: Madhouse'),
        (2, 'Dan Stevens'), (2, 'Rachel House'), (2, 'Rebecca Hall'),
        (3, 'Dichen Lachman'), (3, 'Freya Allan'), (3, 'William H. Macy'),
        (4, 'Aaron Taylor-Johnson'), (4, 'Emily Blunt'), (4, 'Ryan Gosling'),
        (5, 'Cailee Spaeny'), (5, 'Kirsten Dunst'), (5, 'Stephen McKinley Henderson'), (5, 'Wagner Moura'),
        (6, 'Daisy Edgar-Jones'), (6, 'Glen Powell'), (6, 'Katy O\'Brian'),
        (7, 'Finn Wolfhard'), (7, 'Mckenna Grace'), (7, 'Paul Rudd'),
        (8, 'Emma Corrin'), (8, 'Hugh Jackman'), (8, 'Ryan Reynolds'),
        (9, 'Dev Patel'), (9, 'Pitobash'), (9, 'Jatin Malik'),
        (10, 'Ajay Devgn'), (10, 'Madhavan'), (10, 'Jyotika'),
        (11, 'Adain Bradley'), (11, 'Avantika'), (11, 'Harriet Slater'),
        (12, 'Dan Stevens'), (12, 'Rachel House'), (12, 'Rebecca Hall'),
        (13, 'Chris Pratt'), (13, 'Nicholas Hoult'), (13, 'Samuel L. Jackson'),
        (14, 'Dustin Hoffman'), (14, 'Jack Black'), (14, 'Viola Davis'),
        (15, 'Joey King'), (15, 'Kristen Wiig'), (15, 'Steve Carell'),
        (16, 'Alvaro Morte'), (16, 'Simona Tabasco'), (16, 'Sydney Sweeney'),
        (17, 'Djimon Hounsou'), (17, 'Joseph Quinn'), (17, 'Lupita Nyong\'o'),
        (18, 'Dakota Johnson'), (18, 'Isabela Merced'), (18, 'Sydney Sweeney'),
        (19, 'Aaron Pierre'), (19, 'Kelvin Harrison, Jr.'), (19, 'Seth Rogen'),
        (20, 'Bill Nighy'), (20, 'Charles Dance'), (20, 'Nell Tiger Free'),
        (21, 'Liam Hemsworth'), (21, 'Russell Crowe'), (21, 'Luke Hemsworth'),
        (22, 'Madelaine Petsch'), (22, 'Ryan Bown'), (22, 'Matus Lajcak'),
        (23, 'Jason Statham'), (23, 'Emmy Raver-Lampman'), (23, 'Bobby Naderi'),
        (24, 'Nicolas Cage'), (24, 'Jaeden Martell'), (24, 'Maxwell Jenkins'),
        (25, 'Jake Gyllenhaal'), (25, 'Daniela Melchior'), (25, 'Conor McGregor'),
        (26, 'Bill Skarsgård'), (26, 'FKA twigs'), (26, 'Danny Huston'),
        (27, 'Mark Wahlberg'), (27, 'Simu Liu'), (27, 'Juliet Rylance');
        "; 
        $sqlGenreData = 
        "INSERT INTO genre (movie_id, genre) VALUES
        (1, 'Action'), (1, 'Comedy'),
        (2, 'Comedy'), (2, 'Drama'), (2, 'Romance'),
        (3, 'Action'), (3, 'Adventure'),
        (4, 'Action'),
        (5, 'Action'),
        (6, 'Action'),
        (7, 'Fantasy'), (7, 'Adventure'),
        (8, 'Action'), (8, 'Adventure'),
        (9, 'Action'), (9, 'Thriller'),
        (10, 'Horror'), (10, 'Thriller'),
        (11, 'Horror'),
        (12, 'Action'), (12, 'Adventure'),
        (13, 'Animation'),
        (14, 'Animation'),
        (15, 'Animation'),
        (16, 'Horror'),
        (17, 'Horror'),
        (18, 'Action'), (18, 'Adventure'),
        (19, 'Animation'), (19, 'Adventure'), (19, 'Drama'),
        (20, 'Horror'),
        (21, 'Action'), (21, 'Thriller'),
        (22, 'Horror'),
        (23, 'Action'), (23, 'Thriller'),
        (24, 'Action'), (24, 'Horror'), (24, 'Thriller'),
        (25, 'Action'), (25, 'Thriller'),
        (26, 'Action'), (26, 'Crime'), (26, 'Fantasy'),
        (27, 'Adventure'), (27, 'Drama');
        "; 
        $sqlPaymentData =
        "INSERT INTO payments (name_on_card, email_address, card_number, expiration_date, balance, cvv) 
        VALUES ('John Doe', 'john.doe@example.com', '1234567812345678', '2025-12-31', '1000', '123');";
    }    
    
    if (mysqli_query($con, $sqlAdmin) && mysqli_query($con, $sqlUser) && mysqli_query($con, $sqlAdminAcc) && mysqli_query($con, $sqlUserAcc) && mysqli_query($con, $sqlMovie) && mysqli_query($con, $sqlMovieData) && mysqli_query($con, $sqlShowTime) && mysqli_query($con, $sqlShowTimeData) && mysqli_query($con, $sqlSeats) &&  mysqli_query($con, $sqlGenre) && mysqli_query($con, $sqlCast) && mysqli_query($con, $sqlCastData) && mysqli_query($con, $sqlGenreData) && mysqli_query($con, $sqlPayment) && mysqli_query($con, $sqlPaymentData)) {
        echo "Success";
    } else {
        echo "Error creating table: " . mysqli_error($con);
    }
?>

<?php mysqli_close($con); ?>