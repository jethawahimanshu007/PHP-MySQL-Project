DELIMITER |
DROP PROCEDURE IF EXISTS deleteNewNode;
CREATE PROCEDURE deleteNewNode(nodeNameA varchar(100))
BEGIN
DELETE from newNodes where name=nodeNameA;
DELETE FROM NODE_USER where userName=nodeNameA;
END |
DELIMITER ;