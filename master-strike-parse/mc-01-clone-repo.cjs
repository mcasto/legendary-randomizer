const { execSync } = require("child_process");
const { join } = require("path");

execSync(
  `rm -rf master-strike && git clone --depth 1 https://github.com/emfmesquita/master-strike.git`,
  { cwd: __dirname }
);

const msGit = join(__dirname, "master-strike", ".git");

execSync(`rm -rf "${msGit}"`);
