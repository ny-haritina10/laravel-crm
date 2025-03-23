Given those API EndPoint adn the data returned : 

- GET http://127.0.0.1:8080/api/dashboard/monthly-expenses
[
    {
        "month": "2023-10",
        "totalAmount": 451.25
    },
    {
        "month": "2023-11",
        "totalAmount": 550.75
    },
    {
        "month": "2023-12",
        "totalAmount": 750.75
    },
    {
        "month": "2025-03",
        "totalAmount": 10400.0
    }
]


- GET http://127.0.0.1:8080/api/dashboard/budget-evolution
[
    {
        "date": "2023-10",
        "totalBudget": 3500.00
    },
    {
        "date": "2023-11",
        "totalBudget": 2500.00
    },
    {
        "date": "2023-12",
        "totalBudget": 5000.00
    },
    {
        "date": "2025-03",
        "totalBudget": 1000.00
    }
]

- GET http://127.0.0.1:8080/api/dashboard/ticket-status
[
    {
        "status": "archived",
        "count": 1
    },
    {
        "status": "reopened",
        "count": 2
    },
    {
        "status": "escalated",
        "count": 2
    },
    {
        "status": "closed",
        "count": 4
    },
    {
        "status": "pending-customer-response",
        "count": 1
    },
    {
        "status": "assigned",
        "count": 2
    },
    {
        "status": "in-progress",
        "count": 2
    },
    {
        "status": "on-hold",
        "count": 1
    },
    {
        "status": "open",
        "count": 6
    },
    {
        "status": "resolved",
        "count": 4
    }
]

i want you to display 3 chart based on those API endpoint on a laravel blade view : 
- camembert
    . ticket status

- graphe en battonet
    . total depense par mois

- courbe
    . evolution budget des customers

here are the dashboard controller and blade view : 