DELIMITER //

CREATE PROCEDURE sp_GetIndividualComments(IN userId INTEGER, IN userType VARCHAR(20))
BEGIN

IF userType = 'BA' THEN

SELECT comments.id AS id, blog_posts.article_title AS article_title, comments.mod_comment AS mod_comment, comments.ori_comment AS ori_comment, comments.comment_status
FROM comments
INNER JOIN blog_posts
ON comments.blog_id = blog_posts.id
WHERE blog_posts.author = userId ;

ELSE

SELECT comments.id AS id, blog_posts.article_title AS article_title, comments.mod_comment AS mod_comment, comments.ori_comment AS ori_comment, comments.comment_status
FROM comments
INNER JOIN blog_posts
ON comments.blog_id = blog_posts.id;

END IF;

END;

//

DELIMITER ;