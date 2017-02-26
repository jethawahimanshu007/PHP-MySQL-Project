DELIMITER |
DROP PROCEDURE IF EXISTS updateNewNode;
CREATE PROCEDURE updateNewNode(nodeName varchar(100),newParentName varchar(100))
BEGIN

DECLARE parentA VARCHAR(100);

SET parentA=(SELECT parent from newNodes where name=nodeName);

call tempListOfNewNodes(parentA,newParentName);


END |
DELIMITER ;