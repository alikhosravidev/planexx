export default {
  semi: true,
  trailingComma: 'all',
  singleQuote: true,
  printWidth: 80,
  tabWidth: 2,
  endOfLine: 'auto',
  // endOfLine: 'lf',
  overrides: [
    {
      files: '*.sol',
      options: {
        parser: 'solidity-parse',
        printWidth: 80,
        tabWidth: 4,
        useTabs: false,
        singleQuote: true,
        bracketSpacing: false,
      },
    },
  ],
};
