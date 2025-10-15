import { Injectable, NotFoundException } from '@nestjs/common';
import { CreateDiaryDto } from './dto/create-diary.dto';
import { UpdateDiaryDto } from './dto/update-diary.dto';
import { InjectModel } from '@nestjs/mongoose';
import { Diary } from './schemas/diaries.schema';
import { Model } from 'mongoose';

@Injectable()
export class DiariesService {
    constructor(
        @InjectModel('Diary') private readonly diaryModel: Model<Diary>,
    ) {}

    // Create a new diary entry
    async create(createDiaryDto: CreateDiaryDto): Promise<Diary> {
        const newDiary = new this.diaryModel(createDiaryDto);
        return await newDiary.save();
    }

    // Get all diary entries
    async findAll(): Promise<Diary[]> {
        return await this.diaryModel.find().exec();
    }

    // Get a specific diary entry by ID
    async findOne(id: string): Promise<Diary> {
        const diary = await this.diaryModel.findById(id).exec();
        if (!diary) {
            throw new NotFoundException(
                `Diary entry with ID "${id}" not found`,
            );
        }
        return diary;
    }

    // Update a diary entry by ID
    async update(id: string, updateDiaryDto: UpdateDiaryDto): Promise<Diary> {
        const updatedDiary = await this.diaryModel
            .findByIdAndUpdate(id, updateDiaryDto, {
                new: true, // Return the updated document
            })
            .exec();

        if (!updatedDiary) {
            throw new NotFoundException(
                `Diary entry with ID "${id}" not found`,
            );
        }
        return updatedDiary;
    }

    // Delete a diary entry by ID
    async remove(id: string): Promise<Diary> {
        const deletedDiary = await this.diaryModel.findByIdAndDelete(id).exec();
        if (!deletedDiary) {
            throw new NotFoundException(
                `Diary entry with ID "${id}" not found`,
            );
        }
        return deletedDiary;
    }
}
