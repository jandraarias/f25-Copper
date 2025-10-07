<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class OpenAiCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'openai';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle(\OpenAI\Client $client)
    {
        $prompt = 'Make up a place for me to eat lunch, a activity for me to do after, and a place to eat dinner. Give me just the names of these places in a list seperated by commas.';

        $messages = [
            ['role' => 'system', 'content' => 'You are helpful assistant'],
            ['role' => 'user', 'content' => $prompt],
        ];

        $result = $client->chat()->create([
            'messages' => $messages,
            'model' => 'gpt-5-mini',
        ]);

        $this->line(ltrim($result->choices[0]->message->content));

        return Command::SUCCESS;
    }
}
