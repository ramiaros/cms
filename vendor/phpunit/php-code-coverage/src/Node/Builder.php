<?php declare(strict_types=1);
/*
 * This file is part of phpunit/php-code-coverage.
 *
 * (c) Sebastian Bergmann <sebastian@phpunit.de>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */
namespace SebastianBergmann\CodeCoverage\Node;

use const DIRECTORY_SEPARATOR;
use function array_shift;
use function basename;
use function count;
use function dirname;
use function explode;
use function file_exists;
use function implode;
use function str_replace;
use function strpos;
use function substr;
use SebastianBergmann\CodeCoverage\CodeCoverage;
use SebastianBergmann\CodeCoverage\ProcessedCodeCoverageData;
use SebastianBergmann\CodeCoverage\StaticAnalysis\CoveredFileAnalyser;

/**
 * @internal This class is not covered by the backward compatibility promise for phpunit/php-code-coverage
 */
final class Builder
{
    /**
     * @var CoveredFileAnalyser
     */
    private $coveredFileAnalyser;

    public function __construct(CoveredFileAnalyser $coveredFileAnalyser)
    {
        $this->coveredFileAnalyser = $coveredFileAnalyser;
    }

    public function build(CodeCoverage $coverage): Directory
    {
        $data       = clone $coverage->getData(); // clone because path munging is destructive to the original data
        $commonPath = $this->reducePaths($data);
        $root       = new Directory(
            $commonPath,
            null
        );

        $this->addItems(
            $root,
            $this->buildDirectoryStructure($data),
            $coverage->getTests()
        );

        return $root;
    }

    private function addItems(Directory $root, array $items, array $tests): void
    {
        foreach ($items as $key => $value) {
            $key = (string) $key;

            if (substr($key, -2) === '/f') {
                $key      = substr($key, 0, -2);
                $filename = $root->pathAsString() . DIRECTORY_SEPARATOR . $key;

                if (file_exists($filename)) {
                    $root->addFile(
                        new File(
                            $key,
                            $root,
                            $value['lineCoverage'],
                            $value['functionCoverage'],
                            $tests,
                            $this->coveredFileAnalyser->classesIn($filename),
                            $this->coveredFileAnalyser->traitsIn($filename),
                            $this->coveredFileAnalyser->functionsIn($filename),
                            $this->coveredFileAnalyser->linesOfCodeFor($filename)
                        )
                    );
                }
            } else {
                $child = $root->addDirectory($key);

                $this->addItems($child, $value, $tests);
            }
        }
    }

    /**
     * Builds an array representation of the directory structure.
     *
     * For instance,
     *
     * <code>
     * Array
     * (
     *     [Money.php] => Array
     *         (
     *             ...
     *         )
     *
     *     [MoneyBag.php] => Array
     *         (
     *             ...
     *         )
     * )
     * </code>
     *
     * is transformed into
     *
     * <code>
     * Array
     * (
     *     [.] => Array
     *         (
     *             [Money.php] => Array
     *                 (
     *                     ...
     *                 )
     *
     *             [MoneyBag.php] => Array
     *                 (
     *                     ...
     *                 )
     *         )
     * )
     * </code>
     */
    private function buildDirectoryStructure(ProcessedCodeCoverageData $data): array
    {
        $result = [];

        foreach ($data->coveredFiles() as $originalPath) {
            $path    = explode(DIRECTORY_SEPARATOR, $originalPath);
            $pointer = &$result;
            $max     = count($path);

            for ($i = 0; $i < $max; $i++) {
                $type = '';

                if ($i === ($max - 1)) {
                    $type = '/f';
                }

                $pointer = &$pointer[$path[$i] . $type];
            }

            $pointer = [
                'lineCoverage'     => $data->lineCoverage()[$originalPath] ?? [],
                'functionCoverage' => $data->functionCoverage()[$originalPath] ?? [],
            ];
        }

        return $result;
    }

    /**
     * Reduces the paths by cutting the longest common start path.
     *
     * For instance,
     *
     * <code>
     * Array
     * (
     *     [/home/sb/Money/Money.php] => Array
     *         (
     *             ...
     *         )
     *
     *     [/home/sb/Money/MoneyBag.php] => Array
     *         (
     *             ...
     *         )
     * )
     * </code>
     *
     * is reduced to
     *
     * <code>
     * Array
     * (
     *     [Money.php] => Array
     *         (
     *             ...
     *         )
     *
     *     [MoneyBag.php] => Array
     *         (
     *             ...
     *         )
     * )
     * </code>
     */
    private function reducePaths(ProcessedCodeCoverageData $coverage): string
    {
        if (empty($coverage->coveredFiles())) {
            return '.';
        }

        $commonPath = '';
        $paths      = $coverage->coveredFiles();

        if (count($paths) === 1) {
            $commonPath = dirname($paths[0]) . DIRECTORY_SEPARATOR;
            $coverage->renameFile($paths[0], basename($paths[0]));

            return $commonPath;
        }

        $max = count($paths);

        for ($i = 0; $i < $max; $i++) {
            // strip phar:// prefixes
            if (strpos($paths[$i], 'phar://') === 0) {
                $paths[$i] = substr($paths[$i], 7);
                $paths[$i] = str_replace('/', DIRECTORY_SEPARATOR, $paths[$i]);
            }
            $paths[$i] = explode(DIRECTORY_SEPARATOR, $paths[$i]);

            if (empty($paths[$i][0])) {
                $paths[$i][0] = DIRECTORY_SEPARATOR;
            }
        }

        $done = false;
        $max  = count($paths);

        while (!$done) {
            for ($i = 0; $i < $max - 1; $i++) {
                if (!isset($paths[$i][0]) ||
                    !isset($paths[$i + 1][0]) ||
                    $paths[$i][0] !== $paths[$i + 1][0]) {
                    $done = true;

                    break;
                }
            }

            if (!$done) {
                $commonPath .= $paths[0][0];

                if ($paths[0][0] !== DIRECTORY_SEPARATOR) {
                    $commonPath .= DIRECTORY_SEPARATOR;
                }

                for ($i = 0; $i < $max; $i++) {
                    array_shift($paths[$i]);
                }
            }
        }

        $original = $coverage->coveredFiles();
        $max      = count($original);

        for ($i = 0; $i < $max; $i++) {
            $coverage->renameFile($original[$i], implode(DIRECTORY_SEPARATOR, $paths[$i]));
        }

        return substr($commonPath, 0, -1);
    }
}
