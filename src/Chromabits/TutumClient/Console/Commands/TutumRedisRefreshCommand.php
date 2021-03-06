<?php

namespace Chromabits\TutumClient\Console\Commands;

use Chromabits\TutumClient\Entities\ContainerLink;
use Chromabits\TutumClient\Support\EnvUtils;
use Exception;
use Illuminate\Console\Command;

/**
 * Class TutumRedisRefreshCommand
 *
 * @author Eduardo Trujillo <ed@chromabits.com>
 * @package Chromabits\TutumClient\Console\Commands
 */
class TutumRedisRefreshCommand extends Command
{
    /**
     * Name of the command
     *
     * @var string
     */
    protected $name = 'tutum:redis:refresh';

    /**
     * Description of the command
     *
     * @var string
     */
    protected $description = 'Refresh available Redis connections from Tutum';

    /**
     * Execute the command
     *
     * @throws Exception
     */
    public function handle()
    {
        $this->line('Discovering Redis links from Tutum API...');

        $envUtils = new EnvUtils();

        $finder = $this->getLaravel()
            ->make('Chromabits\TutumClient\Cache\TutumRedisPoolFinder');

        $this->line('Container UUID: ' . $envUtils->getContainerUuid());

        $links = $finder->refresh();

        if (is_array($links)) {
            /** @var ContainerLink $link */
            foreach ($links as $link) {
                $urls = $link->getEndpointsAsUrls();

                foreach ($urls as $url) {
                    $this->line(
                        'Found link: ' . $url->getHost() . ':' . $url->getPort()
                    );
                }
            }
        }

        $this->line('Stored discovered links in cache');
    }
}
