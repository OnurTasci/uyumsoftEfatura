# Example



$siparis_no = '12312312312312';
$shipping_address = 'adres';

$urunler = [];

$total = 0;
$tax = 0;
foreach ($products as $key => $produc) {
    $urunler[] = [
        'ad' => $produc->name ?? '',
        'fiyat' => $produc->price,
        'adet' => $produc->quantity,
    ];
    $total += $produc->price;
    $tax = $produc->tax;
}
$tc = '11111111111';
$name = 'ali';
$surname = 'veli';


$invoiceSend = new Uyumsoft();

$response = $invoiceSend->sendInvoice(
    [
        'efaturatutar' => $total,
        'efaturakdv' => $tax,
        'efaturaaciklama' => $siparis_no,
        'faturaisim' => $siparis_no,

        'faturaadres' => $shipping_address['invoice_address'],
        'vergino' => $tc,
        'vergidairesi' => '',
        'efaturanot' => $siparis_no,
        'efaturaad' => $name,
        'efaturasoyad' => $surname,
        'ceptel' => $shipping_address['phone'],
        'eposta' => $shipping_address['email'],
    ]);


print_r($response);
