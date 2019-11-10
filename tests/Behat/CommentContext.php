<?php

declare(strict_types=1);

namespace App\Tests\Behat;

use App\Entity\Post;
use Behat\Behat\Context\Context;
use Behat\Gherkin\Node\PyStringNode;
use Doctrine\Common\Persistence\ManagerRegistry;
use Symfony\Component\BrowserKit\AbstractBrowser;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

final class CommentContext implements Context
{
    private $browser;
    private $managerRegistry;
    private $router;
    private $csrfTokenManager;

    public function __construct(AbstractBrowser $browser, ManagerRegistry $managerRegistry, RouterInterface $router, CsrfTokenManagerInterface $csrfTokenManager)
    {
        $this->browser = $browser;
        $this->managerRegistry = $managerRegistry;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    /**
     * @When I publish a comment to the post :post with body:
     */
    public function iPublishACommentToThePostWithBody(string $post, PyStringNode $body): void
    {
        /** @var Post $post */
        $post = $this->managerRegistry->getRepository(Post::class)->findOneBy(['title' => $post]);
        if (null === $post) {
            throw new \InvalidArgumentException(sprintf('Post "%s" not found', $post));
        }

        $this->browser->request(
            'POST',
            $this->router->generate('comment_new', ['postSlug' => $post->getSlug()]),
            ['comment' => ['content' => $body->getRaw(), '_token' => $this->csrfTokenManager->getToken('comment')->getValue()]]
        );
    }
}
