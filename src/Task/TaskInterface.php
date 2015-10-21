<?php

namespace QualityChecker\Task;

use Doctrine\Common\Collections\ArrayCollection;

use Symfony\Component\Console\Output\OutputInterface;

/**
 * Interface TaskInterface
 *
 * @package QualityChecker\Task
 */
interface TaskInterface
{
    /**
     * @return array
     */
    public function getConfiguration();

    /**
     * @return array
     */
    public function getDefaultConfiguration();

    /**
     * @param OutputInterface $output    Output
     * @param ArrayCollection $appConfig Application config
     *
     * @return boolean
     *
     * @throws \RuntimeException
     */
    public function run(OutputInterface $output, $appConfig);
}
