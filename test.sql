select *
from `users`
where exists(select *
             from `roles`
                      inner join `model_has_roles` on `roles`.`id` = `model_has_roles`.`role_id`
             where `users`.`id` = `model_has_roles`.`model_id`
               and `model_has_roles`.`model_type` = 'App\Models\User' and
    exists (select * from `permissions` inner join `role_has_permissions` on `permissions`.`id` = `role_has_permissions`.`permission_id`
    where `roles`.`id` = `role_has_permissions`.`role_id` and `permissions`.`name` like 'Admin' and `permissions`.`status` = 'active')
    and `model_has_roles`.`team_id` is null and (`roles`.`team_id` is null or `roles`.`team_id` is null))
