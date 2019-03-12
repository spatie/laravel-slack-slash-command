<?php

namespace Spatie\SlashCommand\Test\Handlers;

use Spatie\SlashCommand\Request;
use Spatie\SlashCommand\Response;
use Spatie\SlashCommand\Test\TestCase;
use Spatie\SlashCommand\Exceptions\InvalidHandler;
use Spatie\SlashCommand\Handlers\SignatureHandler;

class SignatureHandlerTest extends TestCase
{
    /** @var \Spatie\SlashCommand\Request */
    protected $request;

    /** @var \Spatie\SlashCommand\Handlers\SignatureHandler */
    protected $signatureHandler;

    public function setUp(): void
    {
        parent::setUp();

        $illuminateRequest = $this->getIlluminateRequest($this->getPostParameters());

        $this->request = Request::createFromIlluminateRequest($illuminateRequest);

        $this->signatureHandler = new class($this->request) extends SignatureHandler {
            public $signature = '/commandName handlerName {argument} {--option} {--another-option}';

            public function handle(Request $request): Response
            {
                return true;
            }
        };
    }

    /** @test */
    public function it_throws_an_exception_if_a_signature_has_not_been_set()
    {
        $this->expectException(InvalidHandler::class);

        new class($this->request) extends SignatureHandler {
            public function handle(Request $request): Response
            {
                return true;
            }
        };
    }

    /** @test */
    public function it_cannot_handle_requests_with_a_command_that_does_not_match_the_signature()
    {
        $signatureHandler = new class($this->request) extends SignatureHandler {
            public $signature = '/commandName another';

            public function handle(Request $request): Response
            {
                return true;
            }
        };

        $this->assertFalse($signatureHandler->canHandle($this->request));

        $signatureHandler = new class($this->request) extends SignatureHandler {
            public $signature = '/another handlerName';

            public function handle(Request $request): Response
            {
                return true;
            }
        };

        $this->assertFalse($signatureHandler->canHandle($this->request));
    }

    /** @test */
    public function it_can_handle_requests_with_a_valid_signature()
    {
        $this->assertTrue($this->signatureHandler->canHandle($this->request));
    }

    /** @test */
    public function it_can_get_the_value_of_an_argument()
    {
        $this->assertSame('my-argument', $this->signatureHandler->getArgument('argument'));
    }

    /** @test */
    public function it_can_get_all_arguments()
    {
        $this->assertSame(['argument' => 'my-argument'], $this->signatureHandler->getArguments());
    }

    /** @test */
    public function it_can_determine_which_options_have_been_set()
    {
        $this->assertTrue($this->signatureHandler->getOption('option'));
        $this->assertFalse($this->signatureHandler->getOption('another-option'));
    }

    /** @test */
    public function it_can_get_all_options()
    {
        $this->assertSame([
            'option' => true,
            'another-option' => false,
        ], $this->signatureHandler->getOptions());
    }

    protected function getPostParameters(array $mergeVariables = []): array
    {
        return array_merge([
            'token' => 'test-token',
            'team_id' => 'T123',
            'team_domain' => 'Company',
            'channel_id' => 'C123',
            'channel_name' => 'General',
            'user_id' => 'U123',
            'user_name' => 'Bob',
            'command' => '/commandName',
            'text' => 'handlerName my-argument --option',
            'response_url' => 'https://slack.com/respond',
        ], $mergeVariables);
    }
}
