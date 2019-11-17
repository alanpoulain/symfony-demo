<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

declare(strict_types=1);

namespace App\Tests\EventSubscriber;

use App\Entity\Comment;
use App\Entity\Post;
use App\Entity\User;
use App\Events\CommentCreatedEvent;
use App\EventSubscriber\CommentNotificationSubscriber;
use PHPUnit\Framework\TestCase;
use Prophecy\Argument;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

final class CommentNotificationSubscriberTest extends TestCase
{
    private $mailerProphecy;
    private $urlGeneratorProphecy;
    private $translatorProphecy;
    private $commentNotificationSubscriber;

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        $this->mailerProphecy = $this->prophesize(MailerInterface::class);
        $this->urlGeneratorProphecy = $this->prophesize(UrlGeneratorInterface::class);
        $this->translatorProphecy = $this->prophesize(TranslatorInterface::class);
        $this->commentNotificationSubscriber = new CommentNotificationSubscriber($this->mailerProphecy->reveal(), $this->urlGeneratorProphecy->reveal(), $this->translatorProphecy->reveal(), 'sender@email.com');
    }

    public function testSendMailOnCommentCreated(): void
    {
        $comment = new Comment();
        $post = new Post();
        $author = new User();
        $author->setEmail('author@email.com');
        $post->setAuthor($author);
        $comment->setPost($post);

        $this->translatorProphecy->trans(Argument::cetera())->willReturn('trans');
        $this->mailerProphecy->send(Argument::type(Email::class))->shouldBeCalledOnce();

        $this->commentNotificationSubscriber->onCommentCreated(new CommentCreatedEvent($comment));
    }
}
