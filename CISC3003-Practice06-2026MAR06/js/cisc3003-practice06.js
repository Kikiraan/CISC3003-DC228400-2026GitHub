let subtotal = 0;

for (let i = 0; i < files.length; i++) {
    const total = calculateTotal(quantities[i], prices[i]);
    subtotal += total;
    outputCartRow(files[i], titles[i], quantities[i], prices[i], total);
}

const tax = subtotal * 0.10;
const shipping = subtotal > 1000 ? 0 : 40;
const grandTotal = subtotal + tax + shipping;
