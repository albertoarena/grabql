<?php
use \GrabQL\Interpreter\Token\Comment;

class CommentTest extends \PHPUnit_Framework_TestCase
{

    /**
     * @covers \GrabQL\Interpreter\Token\Comment::internalProcess
     */
    public function testProcess()
    {
        $comment = new Comment;

        $runtime = new \GrabQL\Runtime\Runtime();
        $token = array();
        $data = array();

        $this->expectOutputString(null);
        $comment->process($runtime, $token, $data);
    }

}