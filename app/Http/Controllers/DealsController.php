<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\deal;
use App\Models\responsible_user;
use App\Models\company;
use App\Models\contact;
use AmoCRM\Collections\CatalogElementsCollection;
use AmoCRM\Collections\Customers\Transactions\TransactionsCollection;
use AmoCRM\Exceptions\AmoCRMApiException;
use AmoCRM\Models\CatalogElementModel;
use AmoCRM\Models\Customers\Transactions\TransactionModel;
use League\OAuth2\Client\Token\AccessTokenInterface;
use AmoCRM\Client\AmoCRMApiClientFactory;
use AmoCRM\OAuth\OAuthConfigInterface;
use AmoCRM\OAuth\OAuthServiceInterface;
use AmoCRM\Collections\Leads\LeadsCollection;
use AmoCRM\Models\LeadModel;

class DealsController extends Controller
{
    public function store_responsible_user($id, $name, $email)
    {
        $responsible_user = responsible_user::updateOrCreate(
            ['id'=>$id],
            ['name'=>$name,'email'=>$email]
        );
    }


    public function store_company($id, $name, $address)
    {
        $company = company::updateOrCreate(
            ['id'=>$id],
            ['name'=>$name,'address'=>$address]
        );
    }


    public function store_contact($id, $name, $phone, $company)
    {
        $contact = contact::updateOrCreate(
            ['id'=>$id],
            ['name'=>$name,'phone'=>$phone,'company'=>$company]
        );
    }


    public function store_deal($id, $name, $price, $responsible_user, $companies)
    {
        $deal = deal::updateOrCreate(
            ['id'=>$id],
            ['name'=>$name,'price'=>$price,'responsible_user'=>$responsible_user,'companies'=>$companies]
        );
    }

    public function oAuthCRMtodb()
    {
        $clientId=env('CRM_CLIENT_ID');
        $clientSecret=env('CRM_SECRET');
        $redirectUri=env('CRM_URL').'/update';
        $apiClient = new \AmoCRM\Client\AmoCRMApiClient($clientId, $clientSecret, $redirectUri);
        
        if (isset($_GET['referer'])) {
            $apiClient->setAccountBaseDomain($_GET['referer']);
        }
        if (!isset($_GET['code'])) {
            $state = bin2hex(random_bytes(16));
            $_SESSION['oauth2state'] = $state;
            if (isset($_GET['button'])) {
                echo $apiClient->getOAuthClient()->getOAuthButton(
                    [
                        'title' => 'Установить интеграцию',
                        'compact' => true,
                        'class_name' => 'className',
                        'color' => 'default',
                        'error_callback' => 'handleOauthError',
                        'state' => $state,
                    ]
                );
                die;
            } else {
                $authorizationUrl = $apiClient->getOAuthClient()->getAuthorizeUrl([
                    'state' => $state,
                    'mode' => 'post_message',
                ]);
                header('Location: ' . $authorizationUrl);
                die;
            }
        } elseif (empty($_GET['state']) || empty($_SESSION['oauth2state']) || ($_GET['state'] !== $_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);
            exit('Invalid state');
        }
        
        $accessToken = $apiClient->getOAuthClient()->getAccessTokenByCode($_GET['code']);
    
        $apiClient->setAccessToken($accessToken);
        return $apiClient;
    }

    public function updateOrCreatetodb($responsibleusersCollection,$companiesCollection,$contactsCollection,$leadsCollection)
    {
        foreach ($responsibleusersCollection as $responsibleuser)
        {
            DealsController::store_responsible_user(
                $responsibleuser->id,
                $responsibleuser->name,
                $responsibleuser->email
            );
        }
        foreach ($companiesCollection as $company)
        {
            DealsController::store_company(
                $company->id,
                $company->name,
                $company->custom_fields_values[0]->values[0]->value
            );
        }
        foreach ($contactsCollection as $contact)
        {
            DealsController::store_contact(
                $contact->id,
                $contact->name,
                $contact->custom_fields_values[0]->values[0]->value,
                $contact->company->id
            );
        }
        foreach ($leadsCollection as $deal)
        {
            DealsController::store_deal(
                $deal->id,
                $deal->name,
                $deal->price,
                $deal->responsible_user_id,
                $deal->company->id
            );
        }
    }


    public function crmtodb()
    {
        $apiClient=DealsController::oAuthCRMtodb();
        
        DealsController::updateOrCreatetodb(
            $apiClient->users()->get(),
            $apiClient->companies()->get(),
            $apiClient->contacts()->get(),
            $apiClient->leads()->get()
        );


        return view('dashboard', ['leads' => deal::all(),'users' => responsible_user::all(),'contacts' => contact::all(),'companies' => company::all()]);
    }
}
