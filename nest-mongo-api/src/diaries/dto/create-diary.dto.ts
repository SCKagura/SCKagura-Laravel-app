import {
    IsString,
    IsNotEmpty,
    IsArray,
    ValidateNested,
    IsOptional,
    IsNumber,
    Min,
    Max,
} from 'class-validator';
import { Type } from 'class-transformer';

class EmotionDto {
    @IsString()
    @IsNotEmpty()
    emotion: string;

    @IsNumber()
    @Min(1)
    @Max(10)
    score: number;
}

export class CreateDiaryDto {
    @IsString()
    @IsNotEmpty()
    author: string;

    @IsString()
    @IsNotEmpty()
    title: string;

    @IsString()
    @IsNotEmpty()
    content: string;

    @IsArray()
    @ValidateNested({ each: true })
    @Type(() => EmotionDto)
    @IsOptional()
    emotions?: EmotionDto[];
}
