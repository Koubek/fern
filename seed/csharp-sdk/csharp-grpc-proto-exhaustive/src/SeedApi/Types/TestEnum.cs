using System.Runtime.Serialization;
using System.Text.Json.Serialization;
using SeedApi.Core;

#nullable enable

namespace SeedApi;

[JsonConverter(typeof(EnumSerializer<TestEnum>))]
public enum TestEnum
{
    [EnumMember(Value = "wire_value")]
    A,

    [EnumMember(Value = "B")]
    B,

    [EnumMember(Value = "C")]
    C,
}
