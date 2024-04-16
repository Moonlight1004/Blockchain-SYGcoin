<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Web3 Pre-sale</title>
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body>
    <button id="connectWalletBtn" class="btn btn-primary mb-3">Connect Wallet</button><br>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h1 class="card-title mb-4">Web3 Pre-sale</h1>
                <div class="row mb-3">
                    <div class="col-md-4">
                        <label for="address" class="form-label">address</label>
                        <input type="text" class="form-control" id="address" placeholder="Address">
                    </div>
                    <div class="col-md-4">
                        <label for="amountInput" class="form-label">Amount</label>
                        <div class="input-group">
                            <input type="number" class="form-control" id="amountInput" placeholder="Enter amount">
                            <button class="btn btn-secondary" id="maxBtn">MAX</button>
                        </div>
                        <label id="balanceDisplay" class="form-label">Balance: 0 SYG</label>
                    </div>
                    <div class="col-md-4">
                        <label for="currencySelect" class="form-label">Currency</label>
                        <select class="form-select" id="currencySelect">
                            <option value="syg">SYG</option>
                        </select>
                    </div>
                </div>
                <button id="buyBtn" class="btn btn-success">Send</button><br>
            </div>
        </div>
    </div>

    <!-- Bootstrap Bundle with Popper -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js"></script>

    <!-- Web3.js -->
    <script src="https://cdn.jsdelivr.net/npm/web3@1.6.0/dist/web3.min.js"></script>
    <script>
        var endereco_carteira;
        var syg_balance;
        var syg_allowance;
        var syg_decimals;

        const syg_contract_address = '0x165b13E5e576a37C0C6F17FACEACbb33d85579d1';
        const move_contract_address = '0xb0b363EDd54E07021473E6E554B81187e311F1AE';

        const syg_contract_abi = [{
                "inputs": [{
                    "internalType": "address",
                    "name": "initialOwner",
                    "type": "address"
                }],
                "stateMutability": "nonpayable",
                "type": "constructor"
            },
            {
                "inputs": [{
                        "internalType": "address",
                        "name": "spender",
                        "type": "address"
                    },
                    {
                        "internalType": "uint256",
                        "name": "allowance",
                        "type": "uint256"
                    },
                    {
                        "internalType": "uint256",
                        "name": "needed",
                        "type": "uint256"
                    }
                ],
                "name": "ERC20InsufficientAllowance",
                "type": "error"
            },
            {
                "inputs": [{
                        "internalType": "address",
                        "name": "sender",
                        "type": "address"
                    },
                    {
                        "internalType": "uint256",
                        "name": "balance",
                        "type": "uint256"
                    },
                    {
                        "internalType": "uint256",
                        "name": "needed",
                        "type": "uint256"
                    }
                ],
                "name": "ERC20InsufficientBalance",
                "type": "error"
            },
            {
                "inputs": [{
                    "internalType": "address",
                    "name": "approver",
                    "type": "address"
                }],
                "name": "ERC20InvalidApprover",
                "type": "error"
            },
            {
                "inputs": [{
                    "internalType": "address",
                    "name": "receiver",
                    "type": "address"
                }],
                "name": "ERC20InvalidReceiver",
                "type": "error"
            },
            {
                "inputs": [{
                    "internalType": "address",
                    "name": "sender",
                    "type": "address"
                }],
                "name": "ERC20InvalidSender",
                "type": "error"
            },
            {
                "inputs": [{
                    "internalType": "address",
                    "name": "spender",
                    "type": "address"
                }],
                "name": "ERC20InvalidSpender",
                "type": "error"
            },
            {
                "inputs": [{
                    "internalType": "address",
                    "name": "owner",
                    "type": "address"
                }],
                "name": "OwnableInvalidOwner",
                "type": "error"
            },
            {
                "inputs": [{
                    "internalType": "address",
                    "name": "account",
                    "type": "address"
                }],
                "name": "OwnableUnauthorizedAccount",
                "type": "error"
            },
            {
                "anonymous": false,
                "inputs": [{
                        "indexed": true,
                        "internalType": "address",
                        "name": "owner",
                        "type": "address"
                    },
                    {
                        "indexed": true,
                        "internalType": "address",
                        "name": "spender",
                        "type": "address"
                    },
                    {
                        "indexed": false,
                        "internalType": "uint256",
                        "name": "value",
                        "type": "uint256"
                    }
                ],
                "name": "Approval",
                "type": "event"
            },
            {
                "anonymous": false,
                "inputs": [{
                        "indexed": true,
                        "internalType": "address",
                        "name": "previousOwner",
                        "type": "address"
                    },
                    {
                        "indexed": true,
                        "internalType": "address",
                        "name": "newOwner",
                        "type": "address"
                    }
                ],
                "name": "OwnershipTransferred",
                "type": "event"
            },
            {
                "anonymous": false,
                "inputs": [{
                        "indexed": true,
                        "internalType": "address",
                        "name": "from",
                        "type": "address"
                    },
                    {
                        "indexed": true,
                        "internalType": "address",
                        "name": "to",
                        "type": "address"
                    },
                    {
                        "indexed": false,
                        "internalType": "uint256",
                        "name": "value",
                        "type": "uint256"
                    }
                ],
                "name": "Transfer",
                "type": "event"
            },
            {
                "inputs": [{
                        "internalType": "address",
                        "name": "owner",
                        "type": "address"
                    },
                    {
                        "internalType": "address",
                        "name": "spender",
                        "type": "address"
                    }
                ],
                "name": "allowance",
                "outputs": [{
                    "internalType": "uint256",
                    "name": "",
                    "type": "uint256"
                }],
                "stateMutability": "view",
                "type": "function"
            },
            {
                "inputs": [{
                        "internalType": "address",
                        "name": "spender",
                        "type": "address"
                    },
                    {
                        "internalType": "uint256",
                        "name": "value",
                        "type": "uint256"
                    }
                ],
                "name": "approve",
                "outputs": [{
                    "internalType": "bool",
                    "name": "",
                    "type": "bool"
                }],
                "stateMutability": "nonpayable",
                "type": "function"
            },
            {
                "inputs": [{
                    "internalType": "address",
                    "name": "account",
                    "type": "address"
                }],
                "name": "balanceOf",
                "outputs": [{
                    "internalType": "uint256",
                    "name": "",
                    "type": "uint256"
                }],
                "stateMutability": "view",
                "type": "function"
            },
            {
                "inputs": [],
                "name": "decimals",
                "outputs": [{
                    "internalType": "uint8",
                    "name": "",
                    "type": "uint8"
                }],
                "stateMutability": "view",
                "type": "function"
            },
            {
                "inputs": [{
                        "internalType": "address",
                        "name": "to",
                        "type": "address"
                    },
                    {
                        "internalType": "uint256",
                        "name": "amount",
                        "type": "uint256"
                    }
                ],
                "name": "mint",
                "outputs": [],
                "stateMutability": "nonpayable",
                "type": "function"
            },
            {
                "inputs": [],
                "name": "name",
                "outputs": [{
                    "internalType": "string",
                    "name": "",
                    "type": "string"
                }],
                "stateMutability": "view",
                "type": "function"
            },
            {
                "inputs": [],
                "name": "owner",
                "outputs": [{
                    "internalType": "address",
                    "name": "",
                    "type": "address"
                }],
                "stateMutability": "view",
                "type": "function"
            },
            {
                "inputs": [],
                "name": "renounceOwnership",
                "outputs": [],
                "stateMutability": "nonpayable",
                "type": "function"
            },
            {
                "inputs": [],
                "name": "symbol",
                "outputs": [{
                    "internalType": "string",
                    "name": "",
                    "type": "string"
                }],
                "stateMutability": "view",
                "type": "function"
            },
            {
                "inputs": [],
                "name": "totalSupply",
                "outputs": [{
                    "internalType": "uint256",
                    "name": "",
                    "type": "uint256"
                }],
                "stateMutability": "view",
                "type": "function"
            },
            {
                "inputs": [{
                        "internalType": "address",
                        "name": "to",
                        "type": "address"
                    },
                    {
                        "internalType": "uint256",
                        "name": "value",
                        "type": "uint256"
                    }
                ],
                "name": "transfer",
                "outputs": [{
                    "internalType": "bool",
                    "name": "",
                    "type": "bool"
                }],
                "stateMutability": "nonpayable",
                "type": "function"
            },
            {
                "inputs": [{
                        "internalType": "address",
                        "name": "from",
                        "type": "address"
                    },
                    {
                        "internalType": "address",
                        "name": "to",
                        "type": "address"
                    },
                    {
                        "internalType": "uint256",
                        "name": "value",
                        "type": "uint256"
                    }
                ],
                "name": "transferFrom",
                "outputs": [{
                    "internalType": "bool",
                    "name": "",
                    "type": "bool"
                }],
                "stateMutability": "nonpayable",
                "type": "function"
            },
            {
                "inputs": [{
                    "internalType": "address",
                    "name": "newOwner",
                    "type": "address"
                }],
                "name": "transferOwnership",
                "outputs": [],
                "stateMutability": "nonpayable",
                "type": "function"
            }
        ];

        const move_contract_abi = [{
                "inputs": [{
                    "internalType": "address",
                    "name": "initialOwner",
                    "type": "address"
                }],
                "stateMutability": "nonpayable",
                "type": "constructor"
            },
            {
                "inputs": [{
                    "internalType": "address",
                    "name": "owner",
                    "type": "address"
                }],
                "name": "OwnableInvalidOwner",
                "type": "error"
            },
            {
                "inputs": [{
                    "internalType": "address",
                    "name": "account",
                    "type": "address"
                }],
                "name": "OwnableUnauthorizedAccount",
                "type": "error"
            },
            {
                "anonymous": false,
                "inputs": [{
                        "indexed": true,
                        "internalType": "address",
                        "name": "previousOwner",
                        "type": "address"
                    },
                    {
                        "indexed": true,
                        "internalType": "address",
                        "name": "newOwner",
                        "type": "address"
                    }
                ],
                "name": "OwnershipTransferred",
                "type": "event"
            },
            {
                "inputs": [{
                        "internalType": "uint256",
                        "name": "_amount",
                        "type": "uint256"
                    },
                    {
                        "internalType": "address",
                        "name": "_to",
                        "type": "address"
                    }
                ],
                "name": "move",
                "outputs": [],
                "stateMutability": "nonpayable",
                "type": "function"
            },
            {
                "inputs": [],
                "name": "owner",
                "outputs": [{
                    "internalType": "address",
                    "name": "",
                    "type": "address"
                }],
                "stateMutability": "view",
                "type": "function"
            },
            {
                "inputs": [],
                "name": "renounceOwnership",
                "outputs": [],
                "stateMutability": "nonpayable",
                "type": "function"
            },
            {
                "inputs": [{
                    "internalType": "address",
                    "name": "_sygContract",
                    "type": "address"
                }],
                "name": "setSygCoinContract",
                "outputs": [],
                "stateMutability": "nonpayable",
                "type": "function"
            },
            {
                "inputs": [],
                "name": "sygCoin",
                "outputs": [{
                    "internalType": "address",
                    "name": "",
                    "type": "address"
                }],
                "stateMutability": "view",
                "type": "function"
            },
            {
                "inputs": [{
                    "internalType": "address",
                    "name": "newOwner",
                    "type": "address"
                }],
                "name": "transferOwnership",
                "outputs": [],
                "stateMutability": "nonpayable",
                "type": "function"
            }
        ];

        $(document).ready(async function () {

            $('#connectWalletBtn').click(function () {
                connectWallet();
            });

            $('#currencySelect').change(function () {
                connectWallet();
            });

            $('#maxBtn').click(function () {
                var valorAtual = $('#balanceDisplay').text();
                $('#amountInput').val(parseFloat(valorAtual.replace(/[^0-9.]/g, '')));
            });

            // Function to connect wallet
            async function connectWallet() {

                switch ($('#currencySelect').val()) {
                    case 'syg':
                        const sygBalance = await saldo_carteira('syg');
                        $('#balanceDisplay').text('Balance : ' + sygBalance + ' SYG');
                        break;
                    case 'matic':
                        const etherBalance = await saldo_carteira('ether');
                        $('#balanceDisplay').text('Balance : ' + etherBalance + ' MATIC');
                        break;
                    default:
                        // Moeda não suportada
                        console.log('Moeda não suportada');
                }
                if (typeof endereco_carteira !== 'undefined' && endereco_carteira !== null &&
                    endereco_carteira !== '') {
                    $("#connectWalletBtn").text(endereco_carteira);
                } else {
                    $("#connectWalletBtn").text('Connect Wallet');
                }
            }

            // Função para obter o saldo da carteira em uma moeda específica
            async function saldo_carteira(moeda) {
                const accounts = await window.ethereum.request({
                    method: 'eth_requestAccounts'
                });

                const account = accounts[0]; // Obtém o primeiro endereço da carteira conectada
                endereco_carteira = account;

                // Inicializa o objeto Web3
                const web3 = new Web3(window.ethereum);
                if (moeda.toLowerCase() === 'ether') {
                    const etherBalance = await web3.eth.getBalance(account);
                    const etherBalanceInEther = web3.utils.fromWei(etherBalance, 'ether');

                    return etherBalanceInEther;
                } else if (moeda.toLowerCase() === 'syg') {
                    const contract = new web3.eth.Contract(syg_contract_abi, syg_contract_address);

                    syg_balance = await contract.methods.balanceOf(account).call();
                    syg_decimals = await contract.methods.decimals().call();
                    syg_allowance = await contract.methods.allowance(account,
                        move_contract_address).call();

                    return syg_balance * 10 ** -syg_decimals;
                } else {
                    // Moeda não suportada
                    return null;
                }
            }

            $('#buyBtn').click(function () {
                var amount = parseFloat($('#amountInput').val());

                if (amount) {
                    const move_amount = amount * 10 ** syg_decimals;
                    const address = $("#address").val()

                    if (syg_balance < move_amount) {
                        window.alert('Balance is not enough');
                    } else {
                        preVenda(move_amount, address);
                    }
                } else {
                    window.alert('Invalid amount');
                }
            }); // Buy end


            async function preVenda(valorComprado, address) {
                const accounts = await window.ethereum.request({
                    method: 'eth_requestAccounts'
                });
                const account = accounts[0]; // Obtém o primeiro endereço da carteira conectada

                const web3 = new Web3(window.ethereum);
                const contratomove = new web3.eth.Contract(
                    move_contract_abi,
                    move_contract_address
                );

                try {
                    if (syg_allowance < valorComprado) {
                        const contratosyg = new web3.eth.Contract(syg_contract_abi,
                            syg_contract_address);
                        const approve_tx = await contratosyg.methods.approve(move_contract_address,
                                syg_balance)
                            .send({
                                from: account
                            });
                    }


                    // Inicia a transação para comprar o token FoxyCoin
                    const transaction = await contratomove.methods.move(valorComprado, address)
                        .send({
                            from: account
                        });
                    console.log('Transação bem-sucedida:', transaction);

                    const sygBalance = await saldo_carteira('syg');
                    $('#balanceDisplay').text('Balance : ' + sygBalance + ' SYG');

                    window.alert('Purchased successfully.');
                } catch (error) {
                    console.error('Erro ao comprar o token SYGCoin:', error);
                }
            }

        });
    </script>

</body>

</html>