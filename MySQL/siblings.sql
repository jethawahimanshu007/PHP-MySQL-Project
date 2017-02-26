	DELIMITER |
	DROP PROCEDURE IF EXISTS siblings;
	CREATE PROCEDURE siblings(parentNodeVal varchar(100))
	BEGIN

	DECLARE nodeTemp VARCHAR(100);
	DECLARE childNodeTemp VARCHAR(100);
	DECLARE parentNodeTemp VARCHAR(100);
	DECLARE i INTEGER;
	DECLARE totalChild INTEGER;
	DECLARE levelVal INTEGER;
	DECLARE parentCounter INTEGER;
	DECLARE totalParents INTEGER;
	DECLARE flag INTEGER;
	DROP TABLE IF EXISTS NODE_LEVEL_TBL;
	CREATE TABLE NODE_LEVEL_TBL(name varchar(100),level integer);


	SET nodeTemp="1";
	SET totalChild=0;
	SET levelVal=1;
	SET parentCounter=0;
	SET flag=0;
	INSERT INTO NODE_LEVEL_TBL VALUES(parentNodeVal,levelVal);
	SET totalParents=(SELECT COUNT(*) FROM NODE_LEVEL_TBL where level=levelVal);
	SET totalChild=(SELECT COUNT(*) from newNodes where parent=nodeTemp);
	loop1: WHILE parentCounter<totalParents
	DO
	SET i=0;

	SET @query=CONCAT("SELECT name INTO @parentNodeTemp from NODE_LEVEL_TBL where level=",levelVal," limit ",parentCounter,",1");
	PREPARE stmt from @query;
	EXECUTE stmt;

	SET totalChild=(SELECT COUNT(*) from newNodes where parent=@parentNodeTemp);
	WHILE i<totalChild
	DO

	SET @query=CONCAT("SELECT name into @childNodeTemp from newNodes where parent=@parentNodeTemp limit ",i,",1");
	PREPARE stmt from @query;
	EXECUTE stmt;

	INSERT INTO NODE_LEVEL_TBL VALUES(@childNodeTemp,levelVal+1);

	SET i=i+1;
	END WHILE;

	SET parentCounter=parentCounter+1;

	IF parentCounter=totalParents
	THEN
	SET levelVal=levelVal+1;
	SET flag=flag+1;
	

	SET totalParents=(SELECT COUNT(*) FROM NODE_LEVEL_TBL where level=levelVal);
	SET parentCounter=0;
	END IF;

	END WHILE;

	SELECT max(level) into @maxLevel from NODE_LEVEL_TBL;
	DELETE FROM NODE_LEVEL_TBL where level=@maxLevel;
	END |
	DELIMITER ;