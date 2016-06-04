/*
  //weatherbits_20160516.sql - first version of WeatherBits application
  
*/

SET foreign_key_checks = 0; #turn off constraints temporarily

#since constraints cause problems, drop tables first, working backward
DROP TABLE IF EXISTS wbit_user;
  
CREATE TABLE wbit_user(
UserID INT UNSIGNED NOT NULL AUTO_INCREMENT,
UserName VARCHAR(60) DEFAULT '',
Email VARCHAR(100) DEFAULT '',
Password VARCHAR(60) DEFAULT '',
DateAdded DATETIME,
LastUpdated TIMESTAMP DEFAULT 0 ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (UserID)
)ENGINE=INNODB; 

/*
#assigning first survey to AdminID == 1
INSERT INTO wbit_user VALUES (NULL,'Test User','thomitchell@gmail.com', 'password',NOW(),NOW()); 
INSERT INTO wbit_user VALUES (NULL,'Test User2','person@gmail.com', 'password2',NOW(),NOW()); 
*/

DROP TABLE IF EXISTS wbit_user_cities;

CREATE TABLE wbit_user_cities(
CitiesID INT UNSIGNED NOT NULL AUTO_INCREMENT,
UserID INT UNSIGNED DEFAULT 0,
CityName TEXT DEFAULT '',
DateAdded DATETIME,
LastUpdated TIMESTAMP DEFAULT 0 ON UPDATE CURRENT_TIMESTAMP,
PRIMARY KEY (CitiesID),
FOREIGN KEY (UserID) REFERENCES wbit_user(UserID) ON DELETE CASCADE
)ENGINE=INNODB;

INSERT INTO wbit_user_cities VALUES (NULL,1,'Seattle',NOW(),NOW());
INSERT INTO wbit_user_cities VALUES (NULL,1,'Jacksonville',NOW(),NOW());
INSERT INTO wbit_user_cities VALUES (NULL,2,'Tacoma',NOW(),NOW());
INSERT INTO wbit_user_cities VALUES (NULL,2,'Miami',NOW(),NOW());


SET foreign_key_checks = 1; #turn foreign key check back on