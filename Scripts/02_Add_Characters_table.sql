USE dragoncontrol
go

CREATE TABLE Characters(
  CharacterID int(11) NOT NULL AUTO_INCREMENT,
  UserID int(11) NOT NULL,
  CharacterName varchar(100) NOT NULL,
  Primary Key (CharacterID),
  CONSTRAINT fk_Characters_UserID FOREIGN KEY (UserID) REFERENCES Users(UserID)
)

CREATE TABLE CharacterAbilities(
  CharacterID int(11) not NULL,
  Strength int(11) NOT NULL,
  Dexterity int(11) NOT NULL,
  Constitution int(11) NOT NULL,
  Intelligence int(11) NOT NULL,
  Wisdom int(11) NOT NULL,
  Charisma int(2) NOT NULL,
  PRIMARY KEY (CharacterID),
  CONSTRAINT fk_CharacterAbilities_CharacterID FOREIGN KEY (CharacterID) REFERENCES Characters(CharacterID)
)
