Usage:
  phpcs [options] <file|directory>

Scan targets:
  <file|directory>               One or more files and/or directories to check, space separated.
  -                              Check STDIN instead of local files and directories.
  --stdin-path=<stdinPath>       If processing STDIN, the file path that STDIN will be processed as.
  --file-list=<fileList>         Check the files and/or directories which are defined in the file to which the path
                                 is provided (one per line).
  --filter=<filter>              Check based on a predefined file filter. Use either the "GitModified" or
                                 "GitStaged" filter, or specify the path to a custom filter class.
  --ignore=<patterns>            Ignore files based on a comma-separated list of patterns matching files and/or
                                 directories.
  --extensions=<extensions>      Check files with the specified file extensions (comma-separated list). Defaults to
                                 php,inc/php,js,css.
                                 The type of the file can be specified using: ext/type; e.g. module/php,es/js.
  -l                             Check local directory only, no recursion.

Rule Selection Options:
  --standard=<standard>          The name of, or the path to, the coding standard to use. Can be a comma-separated
                                 list specifying multiple standards. If no standard is specified, PHP_CodeSniffer
                                 will look for a [.]phpcs.xml[.dist] custom ruleset file in the current directory
                                 and those above it.
  --sniffs=<sniffs>              A comma-separated list of sniff codes to limit the scan to. All sniffs must be part
                                 of the standard in use.
  --exclude=<sniffs>             A comma-separated list of sniff codes to exclude from the scan. All sniffs must be
                                 part of the standard in use.

  -i                             Show a list of installed coding standards.
  -e                             Explain a standard by showing the names of all the sniffs it includes.
  --generator=<generator>        Show documentation for a standard. Use either the "HTML", "Markdown" or "Text"
                                 generator.

Run Options:
  -a                             Run in interactive mode, pausing after each file.
  --bootstrap=<bootstrap>        Run the specified file(s) before processing begins. A list of files can be
                                 provided, separated by commas.
  --cache[=<cacheFile>]          Cache results between runs. Optionally, <cacheFile> can be provided to use a
                                 specific file for caching. Otherwise, a temporary file is used.
  --no-cache                     Do not cache results between runs (default).
  --parallel=<processes>         The number of files to be checked simultaneously. Defaults to 1 (no parallel
                                 processing).
                                 If enabled, this option only takes effect if the PHP PCNTL (Process Control)
                                 extension is available.

  -d <key[=value]>               Set the [key] php.ini value to [value] or set to [true] if value is omitted.
                                 Note: only php.ini settings which can be changed at runtime are supported.

Reporting Options:
  --report=<report>              Print either the "full", "xml", "checkstyle", "csv", "json", "junit", "emacs",
                                 "source", "summary", "diff", "svnblame", "gitblame", "hgblame", "notifysend" or
                                 "performance" report or specify the path to a custom report class. By default, the
                                 "full" report is displayed.
  --report-file=<reportFile>     Write the report to the specified file path.
  --report-<report>=<reportFile> Write the report specified in <report> to the specified file path.
  --report-width=<reportWidth>   How many columns wide screen reports should be. Set to "auto" to use current screen
                                 width, where supported.
  --basepath=<basepath>          Strip a path from the front of file paths inside reports.

  -w                             Include both warnings and errors (default).
  -n                             Do not include warnings. Shortcut for "--warning-severity=0".
  --severity=<severity>          The minimum severity required to display an error or warning. Defaults to 5.
  --error-severity=<severity>    The minimum severity required to display an error. Defaults to 5.
  --warning-severity=<severity>  The minimum severity required to display a warning. Defaults to 5.

  -s                             Show sniff error codes in all reports.
  --ignore-annotations           Ignore all "phpcs:..." annotations in code comments.
  --colors                       Use colors in screen output.
  --no-colors                    Do not use colors in screen output (default).
  -p                             Show progress of the run.
  -q                             Quiet mode; disables progress and verbose output.
  -m                             Stop error messages from being recorded. This saves a lot of memory but stops many
                                 reports from being used.

Configuration Options:
  --encoding=<encoding>          The encoding of the files being checked. Defaults to "utf-8".
  --tab-width=<tabWidth>         The number of spaces each tab represents.

  Default values for a selection of options can be stored in a user-specific CodeSniffer.conf configuration file.
  This applies to the following options: "default_standard", "report_format", "tab_width", "encoding", "severity",
  "error_severity", "warning_severity", "show_warnings", "report_width", "show_progress", "quiet", "colors",
  "cache", "parallel".
  --config-show                  Show the configuration options which are currently stored in the applicable
                                 CodeSniffer.conf file.
  --config-set <key> <value>     Save a configuration option to the CodeSniffer.conf file.
  --config-delete <key>          Delete a configuration option from the CodeSniffer.conf file.
  --runtime-set <key> <value>    Set a configuration option to be applied to the current scan run only.

Miscellaneous Options:
  -h, -?, --help                 Print this help message.
  --version                      Print version information.
  -v                             Verbose output: Print processed files.
  -vv                            Verbose output: Print ruleset and token output.
  -vvv                           Verbose output: Print sniff processing information.

macbookpro@pbrocks keap-connect-wp % phpcs --config-show
Using config file: /usr/local/Cellar/php-code-sniffer/3.10.2/bin/CodeSniffer.conf

default_standard: PSR12

phpcs --config-set default_standard WordPress

installed_paths:  /Users/macbookpro/vendor/phpcsstandards/phpcsutils,/Users/macbookpro/vendor/wp-coding-standards/wpcs
macbookpro@pbrocks keap-connect-wp % 


 ../../phpcsstandards/phpcsextra,../../phpcsstandards/phpcsutils,../../wp-coding-standards/wpcs
