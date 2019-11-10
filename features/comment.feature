Feature: Test the behavior of the comment system

    @profiler
    Scenario: A mail to the post's author is sent when a comment is written
        Given I am authenticated as "john_user@symfony.com"
        When I publish a comment to the post "Lorem ipsum dolor sit amet consectetur adipiscing elit" with body:
        """
        Nice post, thank you!
        """
        Then a mail should have been sent to "jane_admin@symfony.com"
