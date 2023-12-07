DELIMITER //

CREATE PROCEDURE sp_GetBlogComments(IN blogId INTEGER)

BEGIN
	SELECT comments.mod_comment AS commenttext, comments.created_at AS commentdate, CONCAT(users.f_name,' ',users.l_name) AS commentuser
	FROM comments
	INNER JOIN blog_posts
	ON comments.blog_id = blog_posts.id
	INNER JOIN users
	ON users.id = comments.comment_by
	WHERE comments.comment_status = 'PUBLISHED' AND blog_posts.id = blogId;
END;
// 
DELIMITER ;