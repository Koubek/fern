/**
 * This file was auto-generated by Fern from our API Definition.
 */

import * as serializers from "../../../index";
import * as FernIr from "../../../../api/index";
import * as core from "../../../../core";
import { ExampleCodeSampleLanguage } from "./ExampleCodeSampleLanguage";
import { ExampleCodeSampleSdk } from "./ExampleCodeSampleSdk";

export const ExampleCodeSample: core.serialization.Schema<serializers.ExampleCodeSample.Raw, FernIr.ExampleCodeSample> =
    core.serialization
        .union("type", {
            language: ExampleCodeSampleLanguage,
            sdk: ExampleCodeSampleSdk,
        })
        .transform<FernIr.ExampleCodeSample>({
            transform: (value) => {
                switch (value.type) {
                    case "language":
                        return FernIr.ExampleCodeSample.language(value);
                    case "sdk":
                        return FernIr.ExampleCodeSample.sdk(value);
                    default:
                        return value as FernIr.ExampleCodeSample;
                }
            },
            untransform: ({ _visit, ...value }) => value as any,
        });

export declare namespace ExampleCodeSample {
    export type Raw = ExampleCodeSample.Language | ExampleCodeSample.Sdk;

    export interface Language extends ExampleCodeSampleLanguage.Raw {
        type: "language";
    }

    export interface Sdk extends ExampleCodeSampleSdk.Raw {
        type: "sdk";
    }
}
