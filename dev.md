#Role: https://spatie.be/docs/laravel-permission/v5/basic-usage/role-permissions
#Filter: https://spatie.be/docs/laravel-query-builder/v5/features/filtering

#Script js check current auth role: 
 - Check by name: auth.user.roles.find(x => x.name === 'Admin') !== false
 - Check by id: auth.user.roles.find(x => x.id === 1) !== false