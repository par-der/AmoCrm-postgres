<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <div align="center">
        <a href={{env('CRM_URL')}}>Главная страница</a>
    </div>
    <div align="center">
        Текущая БД:
        <table border="20" cellspacing="0" cellpadding="0" width="500" align="center">
            <thead>
                <tr >
                    <th> id </th>
                    <th> name </th>
                    <th> price </th>
                    <th> responsible user </th>
                    <th> company </th>
                </tr>
            </thead>
            <tbody align="center" valign="middle">
                @foreach ($leads as $lead)
                    <tr>
                        <td>{{ $lead->id }}</td>
                        <td>{{ $lead->name }}</td>
                        <td>{{ $lead->price }}</td>
                        <td>{{ $lead->responsible_user }}</td>
                        <td>{{ $lead->companies }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table border="20" cellspacing="0" cellpadding="0" width="500" align="center">
            <thead>
                <tr >
                    <th> id </th>
                    <th> name </th>
                    <th> email </th>
                </tr>
            </thead>
            <tbody align="center" valign="middle">
                @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table border="20" cellspacing="0" cellpadding="0" width="500" align="center">
            <thead>
                <tr >
                    <th> id </th>
                    <th> name </th>
                    <th> address </th>
                </tr>
            </thead>
            <tbody align="center" valign="middle">
                @foreach ($companies as $company)
                    <tr>
                        <td>{{ $company->id }}</td>
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->address }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
        <table border="20" cellspacing="0" cellpadding="0" width="500" align="center">
            <thead>
                <tr >
                    <th> id </th>
                    <th> name </th>
                    <th> phone </th>
                    <th> company </th>
                </tr>
            </thead>
            <tbody align="center" valign="middle">
                @foreach ($contacts as $contact)
                    <tr>
                        <td>{{ $contact->id }}</td>
                        <td>{{ $contact->name }}</td>
                        <td>{{ $contact->phone }}</td>
                        <td>{{ $contact->company }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</html>
