<?php

namespace ConsulConfigManager\Tasks\Services\TaskRunner;

use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Formatter\OutputFormatterStyle;

/**
 * Class LoggableClass
 * @package ConsulConfigManager\Tasks\Services\TaskRunner
 */
abstract class LoggableClass
{
    /**
     * Enable debugging of execution
     * @var bool
     */
    private bool $debug = false;

    /**
     * Output interface used for debugging
     * @var OutputInterface|null
     */
    private ?OutputInterface $output = null;

    /**
     * Enable debug mode
     * @return $this
     */
    public function enableDebug(): self
    {
        $this->debug = true;
        return $this;
    }

    /**
     * Disable debug mode
     * @return $this
     */
    public function disableDebug(): self
    {
        $this->debug = false;
        return $this;
    }

    /**
     * Set debug mode
     * @param bool $debug
     * @return $this
     */
    public function setDebug(bool $debug = false): self
    {
        $this->debug = $debug;
        return $this;
    }

    /**
     * Check whether debug mode is enabled
     * @return bool
     */
    public function getDebug(): bool
    {
        return $this->debug;
    }

    /**
     * Set output interface used for debugging
     * @param OutputInterface|null $output
     * @return $this
     */
    public function setOutputInterface(?OutputInterface $output = null): self
    {
        $this->output = $output;
        return $this;
    }

    /**
     * Get output interface used for debugging
     * @return OutputInterface|null
     */
    public function getOutputInterface(): ?OutputInterface
    {
        return $this->output;
    }

    /**
     * Alias for `getDebug()`
     * @return bool
     */
    public function isDebug(): bool
    {
        return $this->getDebug();
    }

    /**
     * Bootstrap class
     * @return void
     */
    abstract public function bootstrap(): void;

    /**
     * Check whether custom output interface is provided
     * @return bool
     */
    private function hasOutputInterface(): bool
    {
        return $this->output !== null;
    }

    /**
     * Write a blank line.
     *
     * @param  int  $count
     * @return void
     */
    protected function debugNewLine(int $count = 1): void
    {
        if ($this->isDebug()) {
            if ($this->hasOutputInterface()) {
                $this->output->write(str_repeat(PHP_EOL, $count));
            } else {
                for ($index = 0; $index < $count; $index++) {
                    echo PHP_EOL;
                }
            }
        }
    }

    /**
     * Write a string as information output.
     *
     * @param  string  $string
     * @param  int|string|null  $verbosity
     * @return void
     */
    protected function debugInfo(string $string, int|string|null $verbosity = null): void
    {
        $this->debugLine($string, 'info', $verbosity);
    }

    /**
     * Write a string as comment output.
     *
     * @param  string  $string
     * @param  int|string|null  $verbosity
     * @return void
     */
    protected function debugComment(string $string, int|string|null $verbosity = null): void
    {
        $this->debugLine($string, 'comment', $verbosity);
    }

    /**
     * Write a string as error output.
     *
     * @param  string  $string
     * @param  int|string|null  $verbosity
     * @return void
     */
    protected function debugError(string $string, int|string|null $verbosity = null): void
    {
        $this->debugLine($string, 'error', $verbosity);
    }

    /**
     * Write a string as warning output.
     *
     * @param  string  $string
     * @param  int|string|null  $verbosity
     * @return void
     */
    protected function debugWarn(string $string, int|string|null $verbosity = null): void
    {
        if ($this->hasOutputInterface() && $this->isDebug()) {
            if (! $this->output->getFormatter()->hasStyle('warning')) {
                $style = new OutputFormatterStyle('yellow');

                $this->output->getFormatter()->setStyle('warning', $style);
            }
        }
        $this->debugLine($string, 'warning', $verbosity);
    }

    /**
     * Write a string as standard output.
     *
     * @param  string  $string
     * @param  string|null  $style
     * @param  int|string|null  $verbosity
     * @return void
     */
    protected function debugLine(string $string, ?string $style = null, int|string|null $verbosity = null): void
    {
        if (!$this->isDebug()) {
            return;
        }

        if ($this->hasOutputInterface()) {
            $styled = $style ? "<$style>$string</$style>" : $string;
            $this->output->writeln($styled, $this->parseVerbosity($verbosity));
        } else {
            $style = $style ?? 'info';
            echo sprintf(
                '[%s] %s',
                strtoupper($style),
                $string,
            ) . PHP_EOL;
        }
    }

    /**
     * Get the verbosity level in terms of Symfony's OutputInterface level.
     *
     * @param  string|int|null  $level
     * @return int
     */
    private function parseVerbosity(string|int|null $level = null): int
    {
        $verbosityMap = [
            'v' => OutputInterface::VERBOSITY_VERBOSE,
            'vv' => OutputInterface::VERBOSITY_VERY_VERBOSE,
            'vvv' => OutputInterface::VERBOSITY_DEBUG,
            'quiet' => OutputInterface::VERBOSITY_QUIET,
            'normal' => OutputInterface::VERBOSITY_NORMAL,
        ];

        if (isset($verbosityMap[$level])) {
            $level = $verbosityMap[$level];
        } elseif (! is_int($level)) {
            $level = OutputInterface::VERBOSITY_NORMAL;
        }

        return $level;
    }
}
