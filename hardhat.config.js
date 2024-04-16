/** @type import('hardhat/config').HardhatUserConfig */

require("dotenv").config();
require("@nomicfoundation/hardhat-toolbox");

const { MUMBAI_URL, PRIVATE_KEY, BSC_TESTNET_URL, BSC_SCAN_KEY } = process.env;

module.exports = {
  solidity: "0.8.24",
  defaultNetwork: "bsctestnet",
  networks: {
    hardhat: {},
    bsctestnet: { 
      url: BSC_TESTNET_URL,
      chainId: 97,
      accounts: [`0x${PRIVATE_KEY}`],
    },
  },
  etherscan: {
    // apiKey: `${POLYGONSCAN_API_KEY}`, // Your Etherscan API key
    apiKey: `${BSC_SCAN_KEY}`, // Your Etherscan API key
  },
};
