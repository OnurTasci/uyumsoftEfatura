<?php


class Uyumsoft
{
    public $apiURL = "http://edonusumapi.uyum.com.tr/Services/BasicIntegration?singleWsdl";
    public $username = "XXXXXXXXicaret";
    public $password = "XXXXXXXX";

    public $bilgiler = [
        "efirmaad"=>"XXXXX TİCARET İTHALAT İHRACAT LİMİTED ŞİRKETİ",
        "efirmaadres"=>"XXXXXXX. GINZA LAVINYA",
        "efirmavergino"=>"XXXXXXXXXX",
        "efirmaverdaire"=>"BEYLİKDÜZÜ",
        "efirmano"=>"XXX/XXX",
        "efirmailce"=>"BEYLİKDÜZÜ",
        "efirmail"=>"İSTANBUL",
    ];

    public function __construct($username = false,$password = false,$apiURL = false)
    {
        if ($username){ $this->username = $username;}
        if ($password){ $this->password = $password;}
        if($apiURL){$this->apiURL = $apiURL;}


        if ($this->username == 'Uyumsoft'){ // Password Uyumsoft
            $this->apiURL = 'http://efatura-test.uyumsoft.com.tr/Services/BasicIntegration?singleWsdl';
        }
    }



    public   function encode($item)
    {
        $item = iconv("ISO-8859-9","UTF-8",$item);
        return $item;
    }

  public  function encodetesr($item)
    {
        $item = iconv("UTF-8","ISO-8859-9",$item);
        return $item;
    }

    // Curl Çalıştır
    public function sendInvoice($d)
    {

        @ini_set('default_charset', 'UTF-8');

        $WsdlAdres 			= $this->apiURL;
        $WsdlKullaniciAdi 	= $this->username;
        $Wsdlsifre			= $this->password;
        $fattarih=date("Y-m-d");
        $fatsaat=date("H:i:s");

        $efaturatutar= $d['efaturatutar'];
        $efaturakdv= $d['efaturakdv'];
        $efaturaaciklama= $this->encode($d['efaturaaciklama']);
        $faturaisim= $this->encode($d['faturaisim']);
        $faturaadres= $this->encode($d['faturaadres']);
        $vergino= $this->encode($d['vergino']);
        $vergidairesi= $this->encode($d['vergidairesi']);
        $efaturanot= $this->encode($d['efaturanot']);
        $efaturaad= $this->encode($d['efaturaad']);
        $efaturasoyad= $this->encode($d['efaturasoyad']);
        $efaturatel= $d['ceptel'];
        $efaturaeposta= $this->encode($d['eposta']);
        $vergiuzun=strlen($vergino);

        $efirmaad=$this->bilgiler['efirmaad'];
        $efirmaadres=$this->bilgiler['efirmaadres'];
        $efirmavergino=$this->bilgiler['efirmavergino'];
        $efirmaverdaire=$this->bilgiler['efirmaverdaire'];
        $efirmano=$this->bilgiler['efirmano'];
        $efirmailce=$this->bilgiler['efirmailce'];
        $efirmail=$this->bilgiler['efirmail'];


        if($this->username  == 'Uyumsoft'){
            $efirmaad="Radikal Tur Turizm ve Su Sporları LTD. ŞTİ.";
            $efirmaadres="Halitağa Caddesi Bayrameri Sokak.";
            $efirmavergino="9000068418";
            $efirmaverdaire="KADIKÖY";
            $efirmano="1/17";
            $efirmailce="KADIKÖY";
            $efirmail="İSTANBUL";
        }


        $rezervno = '';

        $kdvtut=($efaturatutar*$efaturakdv)/100;
        $netrakam=$efaturatutar-$kdvtut;

        $kdvtutnew=number_format($kdvtut, 2, '.', '');
        $netrakamnew=number_format($netrakam, 2, '.', '');



        if($vergiuzun==10)
        {
            $verdeger="VKN";

            $faturakisi='						
	<PartyName>
	<Name xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$faturaisim.'</Name>
	</PartyName>';


        }
        else
        {

            $verdeger="TCKN";




            $faturakisi='						
	<Person>
	<FirstName xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$efaturaad.'</FirstName>
	<FamilyName xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$efaturasoyad.'</FamilyName>
      </Person>';


        }

        $WsdlXml	='<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
	<s:Body xmlns:xsi="http://www.w3.org/2001/XMLSchema-instance" xmlns:xsd="http://www.w3.org/2001/XMLSchema">
	
		
	
	
		<SendInvoice xmlns="http://tempuri.org/">
			<userInfo Username="'.$WsdlKullaniciAdi.'" Password="'.$Wsdlsifre.'"/>
			<invoices>
				<InvoiceInfo LocalDocumentId="">
					<Invoice>
						<ProfileID xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">TICARIFATURA</ProfileID>
						<ID xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">OKA'.date('Y').$efaturanot.'</ID>
						<CopyIndicator xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">false</CopyIndicator>
						<IssueDate xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$fattarih.'</IssueDate>
						<IssueTime xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$fatsaat.'</IssueTime>
						<InvoiceTypeCode xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">SATIS</InvoiceTypeCode>
						<Note xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$efaturanot.'</Note>
						<Note xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"></Note>					
						<DocumentCurrencyCode xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">TRY</DocumentCurrencyCode>
						<PricingCurrencyCode xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">TRY</PricingCurrencyCode>
						<LineCountNumeric xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">2</LineCountNumeric>
						<OrderReference xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2">
						    <ID xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$rezervno.'</ID>
							<IssueDate xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$fattarih.'</IssueDate>
						</OrderReference>
			
					
						<AccountingSupplierParty xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2">
							<Party>
								<PartyIdentification>
									<ID schemeID="VKN" xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$efirmavergino.'</ID>
								</PartyIdentification>
								<PartyIdentification>
									<ID schemeID="MERSISNO" xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"></ID>
								</PartyIdentification>
								<PartyIdentification>
									<ID schemeID="TICARETSICILNO" xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"></ID>
								</PartyIdentification>
								<PartyName>
									<Name xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$efirmaad.'</Name>
								</PartyName>
								
								
								
								<PostalAddress>
									<Room xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$efirmano.'</Room>
									<StreetName xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$efirmaadres.'</StreetName>
									<BuildingNumber xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$efirmano.'</BuildingNumber>
									<CitySubdivisionName xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$efirmailce.'</CitySubdivisionName>
									<CityName xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$efirmail.'</CityName>
									<Country>
										<Name xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">Türkiye</Name>
									</Country>
								</PostalAddress>
								<PartyTaxScheme>
									<TaxScheme>
										<Name xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$efirmaverdaire.'</Name>
									</TaxScheme>
								</PartyTaxScheme>
							</Party>
						</AccountingSupplierParty>
						<AccountingCustomerParty xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2">
							<Party>
								
								<PartyIdentification>
									<ID schemeID="'.$verdeger.'" xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$vergino.'</ID>
								</PartyIdentification>
								
						
							
							'.$faturakisi.'
							
							
							
	
								
								<PostalAddress>
									<Room xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"></Room>
									<StreetName xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$faturaadres.'</StreetName>
									<BuildingNumber xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"></BuildingNumber>
									<CitySubdivisionName xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"></CitySubdivisionName>
									<CityName xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"></CityName>
									<Country>
										<Name xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">Türkiye</Name>
									</Country>
								</PostalAddress>
								<PartyTaxScheme>
									<TaxScheme>
										<Name xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$vergidairesi.'</Name>
									</TaxScheme>
								</PartyTaxScheme>
								<Contact>
									<Telephone xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$efaturatel.'</Telephone>
									<Telefax xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"></Telefax>
									<ElectronicMail xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$efaturaeposta.'</ElectronicMail>
								</Contact>
							</Party>
						</AccountingCustomerParty>
					
					
						<TaxTotal xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2">
							<TaxAmount currencyID="TRY" xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$kdvtut.'</TaxAmount>
							<TaxSubtotal>
								<TaxAmount currencyID="TRY" xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$kdvtut.'</TaxAmount>
								<Percent xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$efaturakdv.'</Percent>
								<TaxCategory>
									<TaxScheme>
										<Name xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">KDV</Name>
										<TaxTypeCode xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">0015</TaxTypeCode>
									</TaxScheme>
								</TaxCategory>
							</TaxSubtotal>
						</TaxTotal>
						
						
		<LegalMonetaryTotal xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2">
		<LineExtensionAmount currencyID="TRY" xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$netrakam.'</LineExtensionAmount>
		<TaxExclusiveAmount currencyID="TRY" xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$netrakam.'</TaxExclusiveAmount>
		<TaxInclusiveAmount currencyID="TRY" xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$efaturatutar.'</TaxInclusiveAmount>
		<AllowanceTotalAmount currencyID="TRY" xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"></AllowanceTotalAmount>
		<PayableAmount currencyID="TRY" xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$efaturatutar.'</PayableAmount>
		</LegalMonetaryTotal>
		
		<InvoiceLine xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonAggregateComponents-2">
		<ID xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">1</ID>
		<Note xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">Satır Notu</Note>
		<InvoicedQuantity unitCode="NIU" xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">1</InvoicedQuantity>
		<LineExtensionAmount currencyID="TRY" xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$netrakam.'</LineExtensionAmount>
	
		<AllowanceCharge>
		<ChargeIndicator xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">false</ChargeIndicator>
		<Amount currencyID="TRY" xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"></Amount>
		</AllowanceCharge>
		
		<TaxTotal>
		<TaxAmount currencyID="TRY" xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"></TaxAmount>
		<TaxSubtotal>
		<TaxAmount currencyID="TRY" xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$kdvtut.'</TaxAmount>
		<Percent xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$efaturakdv.'</Percent>
		
		<TaxCategory>
		<TaxExemptionReason xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">12345 sayılı kanuna istinaden</TaxExemptionReason>
	
		<TaxScheme>
											<Name xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">KDV</Name>
											<TaxTypeCode xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">0015</TaxTypeCode>
										</TaxScheme>
									</TaxCategory>
								</TaxSubtotal>
							</TaxTotal>
							<Item>
								<Description xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">Açıklama 1</Description>
								<Name xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$efaturaaciklama.'</Name>
								<BrandName xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"></BrandName>
								<ModelName xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"></ModelName>
								<BuyersItemIdentification>
									<ID xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"></ID>
								</BuyersItemIdentification>
								<SellersItemIdentification>
									<ID xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"></ID>
								</SellersItemIdentification>
								<ManufacturersItemIdentification>
									<ID xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2"></ID>
								</ManufacturersItemIdentification>
							</Item>
							<Price>
								<PriceAmount currencyID="TRY" xmlns="urn:oasis:names:specification:ubl:schema:xsd:CommonBasicComponents-2">'.$netrakam.'</PriceAmount>
							</Price>
						</InvoiceLine>
						
						
				
					</Invoice>
					<TargetCustomer '.$verdeger.'="'.$vergino.'" Alias="defaultpk" Title="'.$faturaisim.'"/>
					<EArchiveInvoiceInfo DeliveryType="Electronic"/>
					<Scenario>Automated</Scenario>
				</InvoiceInfo>
			</invoices>
		</SendInvoice>
	</s:Body>
</s:Envelope>';

        $WsdlBaslik 			= array(	"Content-type: text/xml;charset=\"utf-8\"",
            "Accept: text/xml",
            "Cache-Control: no-cache",
            "Pragma: no-cache",
            "Content-length: ".strlen($WsdlXml),
            "SOAPAction: http://tempuri.org/IBasicIntegration/SendInvoice"
        );



        $WsdlBaglan = curl_init();
        curl_setopt($WsdlBaglan, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($WsdlBaglan, CURLOPT_SSL_VERIFYHOST, false);

        curl_setopt($WsdlBaglan, CURLOPT_URL, $WsdlAdres);
        curl_setopt($WsdlBaglan, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($WsdlBaglan, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($WsdlBaglan, CURLOPT_AUTOREFERER, true);
        curl_setopt($WsdlBaglan, CURLOPT_USERPWD, $WsdlKullaniciAdi.":".$Wsdlsifre);
        curl_setopt($WsdlBaglan, CURLOPT_HTTPAUTH, CURLAUTH_ANY);
        curl_setopt($WsdlBaglan, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($WsdlBaglan, CURLOPT_TIMEOUT, 10);
        curl_setopt($WsdlBaglan, CURLOPT_POST, true);
        curl_setopt($WsdlBaglan, CURLOPT_HTTPGET, false);
        curl_setopt($WsdlBaglan, CURLOPT_VERBOSE, true);
        curl_setopt($WsdlBaglan, CURLOPT_HEADER, false);
        curl_setopt($WsdlBaglan, CURLINFO_HEADER_OUT, true);
        curl_setopt($WsdlBaglan, CURLOPT_POSTFIELDS, $WsdlXml);
        curl_setopt($WsdlBaglan, CURLOPT_HTTPHEADER, $WsdlBaslik);





        $WsdlSonuc = curl_exec($WsdlBaglan);
        curl_close($WsdlBaglan);

       // dd($WsdlSonuc,$d);

        $WsdlSonuc = str_replace("<s:Body","<body",$WsdlSonuc);
        $WsdlSonuc = str_replace("</s:Body","</body>",$WsdlSonuc);
        $WsdlSonuc = simplexml_load_string($WsdlSonuc);


        $sonuc=$WsdlSonuc->body->SendInvoiceResponse->SendInvoiceResult["IsSucceded"] ?? '';
        $hata=$WsdlSonuc->body->SendInvoiceResponse->SendInvoiceResult["Message"] ?? '';
        $efatid=$WsdlSonuc->body->SendInvoiceResponse->SendInvoiceResult->Value["Id"] ?? '';
        $efatno=$WsdlSonuc->body->SendInvoiceResponse->SendInvoiceResult->Value["Number"] ?? '';



        if($sonuc=='true')
        {
            return [true,$efatno,$efatid];
            echo "<p style='text-align:center; font-size:25px; font-weight:600; margin-top:25px; font-family:Arial, Helvetica, sans-serif;color:#090;'>".$this->encodetesr("FATURA BAŞARIYLA OLUŞTURULDU")."</p><p style='text-align:center;font-family:Arial, Helvetica, sans-serif; '><b>Fatur No:</b> </p>";
        }
        else
        {
            return [false,$this->encodetesr($hata)];
        }

    }
}
