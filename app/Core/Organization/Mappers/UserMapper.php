<?php

declare(strict_types=1);

namespace App\Core\Organization\Mappers;

use App\Core\Organization\Entities\User;
use App\Core\Organization\Enums\GenderEnum;
use App\Core\Organization\Enums\UserTypeEnum;
use App\Domains\User\UserUpsertDTO;
use App\Utilities\OrNull;
use App\ValueObjects\Email;
use App\ValueObjects\Mobile;
use App\ValueObjects\NationalCode;
use Illuminate\Http\Request;

class UserMapper
{
    public function fromRequest(Request $request): UserUpsertDTO
    {
        return new UserUpsertDTO(
            fullName: OrNull::stringOrNull($request->input('full_name')),
            mobile: OrNull::valueObjectOrNull($request->input('mobile'), Mobile::class),
            firstName: $request->input('first_name'),
            lastName: $request->input('last_name'),
            email: OrNull::valueObjectOrNull($request->input('email'), Email::class),
            nationalCode: OrNull::valueObjectOrNull($request->input('national_code'), NationalCode::class),
            gender: $request->filled('gender') ? GenderEnum::from((int) $request->input('gender')) : null,
            birthDate: OrNull::dateOrNull($request->input('birth_date')),
            userType: $request->filled('user_type') ? UserTypeEnum::fromName($request->input('user_type')) : null,
            isActive: OrNull::boolOrNull($request->input('is_active')),
            password: OrNull::stringOrNull($request->input('password')),
            directManagerId: OrNull::intOrNull($request->input('direct_manager_id')),
            departmentId: OrNull::intOrNull($request->input('department_id')),
            employmentDate: OrNull::dateOrNull($request->input('employment_date')),
            employeeCode: $request->input('employee_code'),
            imageUrl: $request->input('image_url'),
        );
    }

    public function fromRequestForUpdate(Request $request, User $user): UserUpsertDTO
    {
        return new UserUpsertDTO(
            fullName: OrNull::stringOrNull($request->input('full_name'))                                   ?? $user->full_name,
            mobile: OrNull::valueObjectOrNull($request->input('mobile'), Mobile::class)                    ?? $user->mobile,
            firstName: $request->input('first_name')                                                       ?? $user->first_name,
            lastName: $request->input('last_name')                                                         ?? $user->last_name,
            email: OrNull::valueObjectOrNull($request->input('email'), Email::class)                       ?? $user->email,
            nationalCode: OrNull::valueObjectOrNull($request->input('national_code'), NationalCode::class) ?? $user->national_code,
            gender: $request->filled('gender') ? GenderEnum::from((int) $request->input('gender')) : $user->gender,
            birthDate: OrNull::dateOrNull($request->input('birth_date')) ?? $user->birth_date,
            userType: $request->filled('user_type') ? UserTypeEnum::fromName($request->input('user_type')) : $user->user_type,
            isActive: OrNull::boolOrNull($request->input('is_active')) ?? $user->is_active,
            password: OrNull::stringOrNull($request->input('password')),
            directManagerId: OrNull::intOrNull($request->input('direct_manager_id')) ?? $user->direct_manager_id,
            departmentId: OrNull::intOrNull($request->input('department_id')),
            employmentDate: OrNull::dateOrNull($request->input('employment_date')),
            employeeCode: $request->input('employee_code'),
            imageUrl: $request->input('image_url') ?? $user->image_url,
        );
    }
}
