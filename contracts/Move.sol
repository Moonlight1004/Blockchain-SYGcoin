// SPDX-License-Identifier: MIT
// Compatible with OpenZeppelin Contracts ^5.0.0
pragma solidity ^0.8.18;

import "@openzeppelin/contracts/token/ERC20/ERC20.sol";
import "@openzeppelin/contracts/token/ERC20/extensions/ERC20Burnable.sol";
import "@openzeppelin/contracts/access/Ownable.sol";
import "@openzeppelin/contracts/token/ERC20/extensions/ERC20Permit.sol";

contract Move is Ownable {

    address public sygCoin = 0x165b13E5e576a37C0C6F17FACEACbb33d85579d1; 

    constructor(address initialOwner) 
        Ownable(initialOwner)
    {}



    function setSygCoinContract(address _sygContract) public onlyOwner {
        sygCoin = _sygContract;
    }


    function move(uint256 _amount, address _to) external {

        ERC20 sygContract = ERC20(sygCoin);


        require(sygContract.balanceOf(msg.sender) >= _amount, "SYG balance is not enough"); 


        uint256 allowance = sygContract.allowance(msg.sender, address(this));
        require(allowance >= _amount, "Allowance of SYG is not valid");


        require(sygContract.transferFrom(msg.sender, _to, _amount), "Failed to transfer SYGs");

    }
}
