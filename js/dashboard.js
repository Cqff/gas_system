document.addEventListener('DOMContentLoaded', function() {
    loadOrders();
});

function loadOrders() {
    fetch('php/getOrders.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.querySelector('#orderTable tbody');
            tableBody.innerHTML = '';
            data.forEach(order => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${order.ORDER_Id}</td>
                    <td>${order.CUSTOMER_Id}</td>
                    <td>${order.CUSTOMER_PhoneNo}</td>
                    <td>${order.DELIVERY_Address}</td>
                    <td>${order.送貨日期}</td>
                    <td>${order.送貨時間}</td>
                    <td>${order.CUSTOMER_Name}</td>
                    <td>${order.Order_type}</td>
                    <td>${order.Order_weight}</td>
                    <td>${order.Gas_Quantity}</td>
                    <td>${order.Gas_Volume}</td>
                    <td>${order.WORKER_Name}</td>
                    <td>${order.sensor_id}</td>
                    <td>${order.CurrentGasAmount}</td>
                    <td>${order.Exchange}</td>
                `;
                tableBody.appendChild(row);
            });
        });
}

function submitOrder() {
    const orderData = {
        orderDate: document.getElementById('order-date').value,
        customerName: document.getElementById('customer-name').value,
        orderContent: document.getElementById('order-content').value,
        quantity: document.getElementById('quantity').value,
        unit: document.getElementById('unit').value,
        unitPrice: document.getElementById('unit-price').value,
        totalPrice: document.getElementById('total-price').value,
        status: document.getElementById('status').value,
        remarks: document.getElementById('remarks').value
    };

    fetch('php/submitOrder.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json'
        },
        body: JSON.stringify(orderData)
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Order submitted successfully');
            loadOrders();
        } else {
            alert('Failed to submit order');
        }
    });
}