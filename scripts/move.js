// We require the Hardhat Runtime Environment explicitly here. This is optional
// but useful for running the script in a standalone fashion through `node <script>`.
//
// You can also run a script with `npx hardhat run <script>`. If you do that, Hardhat
// will compile your contracts, add the Hardhat Runtime Environment's members to the
// global scope, and execute the script.
const hardhat = require("hardhat");

async function main() {
  const move = await hardhat.ethers.getContractFactory("Move");
  console.log(`Deploying contract with the account: ${move.runner.address}`);

  const deplyedmove = await move.deploy(move.runner.address);
  await deplyedmove.waitForDeployment();

  let deplyedmoveAddress = await deplyedmove.getAddress();
  console.log(`Contract deployed to ${deplyedmoveAddress} on ${hardhat.network.name}`);

  const WAIT_BLOCK_CONFIRMATIONS = 6;
  await deplyedmove.deploymentTransaction().wait(WAIT_BLOCK_CONFIRMATIONS);

  console.log(`Verifying contract on PolygonScan...`);
  await hardhat.run(`verify:verify`, {
    address: deplyedmoveAddress,
    constructorArguments: [
      move.runner.address,
    ],
  });
}

// We recommend this pattern to be able to use async/await everywhere
// and properly handle errors.
main().catch((error) => {
  console.error(error);
  process.exitCode = 1;
});