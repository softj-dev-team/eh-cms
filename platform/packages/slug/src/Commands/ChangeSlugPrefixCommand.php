<?php

namespace Botble\Slug\Commands;

use Botble\Slug\Repositories\Interfaces\SlugInterface;
use Illuminate\Console\Command;

class ChangeSlugPrefixCommand extends Command
{

    /**
     * The console command signature.
     *
     * @var string
     */
    protected $signature = 'cms:slug:prefix {reference : screen name of that object} {--prefix= : The prefix of slugs}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Change/set prefix for slugs';

    /**
     * Execute the console command.
     * @author Sang Nguyen
     */
    public function handle()
    {
        if (!preg_match('/^[a-z\-_]+$/i', $this->argument('reference'))) {
            $this->error('Only alphabetic characters are allowed.');
            return false;
        }

        $data = app(SlugInterface::class)->update(
            [
                'reference' => $this->argument('reference'),
            ],
            [
                'prefix' => $this->option('prefix') ?? '',
            ]
        );

        $this->info('Processed ' . $data . ' item(s)!');

        return true;
    }
}
