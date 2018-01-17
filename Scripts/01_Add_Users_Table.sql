CREATE TABLE Users(
  UserID int(1) NOT NULL AUTO_INCREMENT,
  UserName varchar(50) NOT NULL UNIQUE,
  Password varchar(60) NOT NULL,
  IsAdmin boolean DEFAULT 0,
  Primary Key (UserID)
)

/* Insert default users */
INSERT INTO
users (UserID, UserName, Password, IsAdmin)
values
('admin','$2y$10$sSCaB9qWEl90/1NeygJyBevOPuDzjcCGqozV3HdYxO6cCjDQ.qxZ6', 1)
